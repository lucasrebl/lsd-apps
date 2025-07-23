<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\TournoiType;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tournoi')]
class TournoiController extends AbstractController
{
    #[Route('/', name: 'tournoi_index', methods: ['GET'])]
    public function index(TournoiRepository $tournoiRepository): Response
    {
        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournoiRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'tournoi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tournoi);
            $em->flush();
            return $this->redirectToRoute('tournoi_index');
        }

        return $this->render('tournoi/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'tournoi_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Tournoi $tournoi, EntityManagerInterface $em, \App\Repository\EquipeRepository $equipeRepository): Response
    {
        // Liste des équipes déjà inscrites
        $equipesInscrites = $tournoi->getEquipesInscrites();
        // Liste des équipes non inscrites
        $equipesNonInscrites = $equipeRepository->createQueryBuilder('e')
            ->where(':tournoi NOT MEMBER OF e.tournoisInscrits')
            ->setParameter('tournoi', $tournoi)
            ->getQuery()
            ->getResult();

        if ($request->isMethod('POST')) {
            $equipeId = $request->request->get('equipe_id');
            if ($equipeId) {
                $equipe = $equipeRepository->find($equipeId);
                if ($equipe) {
                    $tournoi->getEquipesInscrites()->add($equipe);
                    $equipe->addTournoiInscrit($tournoi);
                    $em->persist($tournoi);
                    $em->persist($equipe);
                    $em->flush();
                    $this->addFlash('success', 'Équipe inscrite avec succès !');
                    return $this->redirectToRoute('tournoi_show', ['id' => $tournoi->getId()]);
                }
            }
        }

        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
            'equipesInscrites' => $equipesInscrites,
            'equipesNonInscrites' => $equipesNonInscrites,
        ]);
    }

    #[Route('/{id}/generer-poules', name: 'tournoi_generer_poules', methods: ['POST'])]
    public function genererPoules(Request $request, Tournoi $tournoi, EntityManagerInterface $em): Response
    {
        $nbParPoule = (int) $request->request->get('nb_equipes_par_poule');
        $equipes = $tournoi->getEquipesInscrites()->toArray();
        shuffle($equipes);
        $nbEquipes = count($equipes);
        if ($nbParPoule < 2 || $nbParPoule > $nbEquipes) {
            $this->addFlash('danger', 'Nombre d\'équipes par poule invalide.');
            return $this->redirectToRoute('tournoi_show', ['id' => $tournoi->getId()]);
        }
        $nbPoules = (int) ceil($nbEquipes / $nbParPoule);
        $pouleIndex = 1;
        for ($i = 0; $i < $nbPoules; $i++) {
            $poule = new \App\Entity\Poule();
            $poule->setNom('Poule ' . chr(65 + $i));
            $poule->setIdTournoi($tournoi);
            $poule->setDateCreation(new \DateTime());
            $em->persist($poule);
            // Répartir les équipes dans la poule
            for ($j = 0; $j < $nbParPoule && ($i * $nbParPoule + $j) < $nbEquipes; $j++) {
                $equipe = $equipes[$i * $nbParPoule + $j];
                $pouleEquipe = new \App\Entity\PouleEquipe();
                $pouleEquipe->setIdPoule($poule);
                $pouleEquipe->setIdEquipe($equipe);
                $pouleEquipe->setDateCreation(new \DateTime());
                $em->persist($pouleEquipe);
            }
        }
        $em->flush();
        $this->addFlash('success', 'Poules générées aléatoirement !');
        return $this->redirectToRoute('tournoi_show', ['id' => $tournoi->getId()]);
    }

    #[Route('/{id}/generer-matchs', name: 'tournoi_generer_matchs', methods: ['POST'])]
    public function genererMatchs(Request $request, Tournoi $tournoi, EntityManagerInterface $em): Response
    {
        foreach ($tournoi->getPoules() as $poule) {
            $equipes = [];
            foreach ($poule->getPouleEquipes() as $pe) {
                $equipes[] = $pe->getIdEquipe();
            }
            $nbEquipes = count($equipes);
            for ($i = 0; $i < $nbEquipes - 1; $i++) {
                for ($j = $i + 1; $j < $nbEquipes; $j++) {
                    $match = new \App\Entity\Matchs();
                    $match->setIdPoule($poule);
                    $match->setIdTournoi($tournoi);
                    $match->setDateHeure(new \DateTime());
                    $match->setLieu($tournoi->getLieu());
                    $match->setPhase('poule');
                    $match->setDateCreation(new \DateTime());
                    $em->persist($match);
                    // Lier les deux équipes au match
                    foreach ([['equipe' => $equipes[$i], 'role' => 'A'], ['equipe' => $equipes[$j], 'role' => 'B']] as $data) {
                        $me = new \App\Entity\MatchEquipe();
                        $me->setIdMatch($match);
                        $me->setIdEquipe($data['equipe']);
                        $me->setRole($data['role']);
                        $me->setDateCreation(new \DateTime());
                        $em->persist($me);
                    }
                }
            }
        }
        $em->flush();
        $this->addFlash('success', 'Tous les matchs de poule ont été générés !');
        return $this->redirectToRoute('tournoi_show', ['id' => $tournoi->getId()]);
    }

    #[Route('/match/{id}/saisir-resultat', name: 'match_saisir_resultat', methods: ['GET', 'POST'])]
    public function saisirResultat(Request $request, \App\Entity\Matchs $match, EntityManagerInterface $em): Response
    {
        $resultat = $match->getResultat();
        if (!$resultat) {
            $resultat = new \App\Entity\Resultat();
            $resultat->setMatch($match);
        }
        if ($request->isMethod('POST')) {
            $scoreA = (int) $request->request->get('score_equipeA');
            $scoreB = (int) $request->request->get('score_equipeB');
            $fautesA = (int) $request->request->get('fautes_equipeA');
            $fautesB = (int) $request->request->get('fautes_equipeB');
            $jaunesA = (int) $request->request->get('cartons_jaunes_equipeA');
            $jaunesB = (int) $request->request->get('cartons_jaunes_equipeB');
            $rougesA = (int) $request->request->get('cartons_rouges_equipeA');
            $rougesB = (int) $request->request->get('cartons_rouges_equipeB');
            $resultat->setScoreEquipe1($scoreA);
            $resultat->setScoreEquipe2($scoreB);
            $resultat->setFautesEquipe1($fautesA);
            $resultat->setFautesEquipe2($fautesB);
            $resultat->setCartonsJaunesEquipe1($jaunesA);
            $resultat->setCartonsJaunesEquipe2($jaunesB);
            $resultat->setCartonsRougesEquipe1($rougesA);
            $resultat->setCartonsRougesEquipe2($rougesB);
            $resultat->setDateCreation(new \DateTime());
            $em->persist($resultat);
            $em->flush();
            $this->addFlash('success', 'Résultat enregistré !');
            return $this->redirectToRoute('tournoi_show', ['id' => $match->getIdTournoi()->getId()]);
        }
        // Récupérer les noms des équipes A et B
        $equipeA = null;
        $equipeB = null;
        foreach ($match->getMatchEquipes() as $me) {
            if ($me->getRole() === 'A') $equipeA = $me->getIdEquipe();
            if ($me->getRole() === 'B') $equipeB = $me->getIdEquipe();
        }
        return $this->render('tournoi/saisir_resultat.html.twig', [
            'match' => $match,
            'equipeA' => $equipeA,
            'equipeB' => $equipeB,
            'resultat' => $resultat,
        ]);
    }
} 