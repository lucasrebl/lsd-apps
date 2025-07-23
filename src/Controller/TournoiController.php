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

        // Préparer les matchs du tableau à élimination directe groupés par tour
        $tableauTours = [];
        foreach ($tournoi->getTableaus() as $tableau) {
            foreach ($tableau->getMatchs() as $match) {
                $phase = $match->getPhase() ?: 'Tour inconnu';
                if (!isset($tableauTours[$tableau->getId()])) {
                    $tableauTours[$tableau->getId()] = [];
                }
                if (!isset($tableauTours[$tableau->getId()][$phase])) {
                    $tableauTours[$tableau->getId()][$phase] = [];
                }
                $tableauTours[$tableau->getId()][$phase][] = $match;
            }
        }
        // Classement des poules
        $classementsPoules = [];
        foreach ($tournoi->getPoules() as $poule) {
            $classement = [];
            foreach ($poule->getPouleEquipes() as $pe) {
                $equipe = $pe->getIdEquipe();
                $points = 0;
                $victoires = 0;
                $buts = 0;
                foreach ($poule->getMatchs() as $match) {
                    $res = $match->getResultat();
                    if ($res) {
                        foreach ($match->getMatchEquipes() as $me) {
                            if ($me->getIdEquipe() === $equipe) {
                                if ($me->getRole() === 'A') {
                                    $score = $res->getScoreEquipe1();
                                    $scoreAdv = $res->getScoreEquipe2();
                                } else {
                                    $score = $res->getScoreEquipe2();
                                    $scoreAdv = $res->getScoreEquipe1();
                                }
                                if ($score > $scoreAdv) {
                                    $points += 3;
                                    $victoires++;
                                } elseif ($score == $scoreAdv) {
                                    $points += 1;
                                }
                                $buts += $score;
                            }
                        }
                    }
                }
                $classement[] = [
                    'equipe' => $equipe,
                    'points' => $points,
                    'victoires' => $victoires,
                    'buts' => $buts,
                ];
            }
            usort($classement, function($a, $b) {
                if ($a['points'] === $b['points']) {
                    if ($a['victoires'] === $b['victoires']) {
                        return $b['buts'] <=> $a['buts'];
                    }
                    return $b['victoires'] <=> $a['victoires'];
                }
                return $b['points'] <=> $a['points'];
            });
            $classementsPoules[$poule->getId()] = $classement;
        }
        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
            'equipesInscrites' => $tournoi->getEquipesInscrites(),
            'equipesNonInscrites' => $equipeRepository->createQueryBuilder('e')
                ->where(':tournoi NOT MEMBER OF e.tournoisInscrits')
                ->setParameter('tournoi', $tournoi)
                ->getQuery()
                ->getResult(),
            'tableauTours' => $tableauTours,
            'classementsPoules' => $classementsPoules,
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

    #[Route('/{id}/generer-tableau', name: 'tournoi_generer_tableau', methods: ['POST'])]
    public function genererTableau(Request $request, Tournoi $tournoi, EntityManagerInterface $em): Response
    {
        $nbQualifiesParPoule = (int) $request->request->get('nb_qualifies', 1);
        // 1. Récupérer les équipes qualifiées (top N de chaque poule)
        $equipesQualifiees = [];
        foreach ($tournoi->getPoules() as $poule) {
            $classement = [];
            foreach ($poule->getPouleEquipes() as $pe) {
                $equipe = $pe->getIdEquipe();
                $points = 0;
                $buts = 0;
                foreach ($poule->getMatchs() as $match) {
                    $res = $match->getResultat();
                    if ($res) {
                        foreach ($match->getMatchEquipes() as $me) {
                            if ($me->getIdEquipe() === $equipe) {
                                if ($me->getRole() === 'A') {
                                    $points += $res->getScoreEquipe1() > $res->getScoreEquipe2() ? 3 : ($res->getScoreEquipe1() == $res->getScoreEquipe2() ? 1 : 0);
                                    $buts += $res->getScoreEquipe1();
                                } else {
                                    $points += $res->getScoreEquipe2() > $res->getScoreEquipe1() ? 3 : ($res->getScoreEquipe1() == $res->getScoreEquipe2() ? 1 : 0);
                                    $buts += $res->getScoreEquipe2();
                                }
                            }
                        }
                    }
                }
                $classement[] = ['equipe' => $equipe, 'points' => $points, 'buts' => $buts];
            }
            usort($classement, function($a, $b) {
                if ($a['points'] === $b['points']) return $b['buts'] <=> $a['buts'];
                return $b['points'] <=> $a['points'];
            });
            foreach (array_slice($classement, 0, $nbQualifiesParPoule) as $row) {
                $equipesQualifiees[] = $row['equipe'];
            }
        }
        // 2. Générer le tableau à élimination directe
        shuffle($equipesQualifiees);
        $nbEquipes = count($equipesQualifiees);
        if ($nbEquipes < 2) {
            $this->addFlash('danger', 'Pas assez d\'équipes qualifiées pour générer un tableau.');
            return $this->redirectToRoute('tournoi_show', ['id' => $tournoi->getId()]);
        }
        $tableau = new \App\Entity\Tableau();
        $tableau->setNomPhase('Phase finale');
        $tableau->setIdTournoi($tournoi);
        $tableau->setDateCreation(new \DateTime());
        $em->persist($tableau);
        $tour = 1;
        $equipesTour = $equipesQualifiees;
        while (count($equipesTour) > 1) {
            $nbMatchs = intdiv(count($equipesTour), 2);
            for ($i = 0; $i < $nbMatchs; $i++) {
                $match = new \App\Entity\Matchs();
                $match->setIdTableau($tableau);
                $match->setIdTournoi($tournoi);
                $match->setDateHeure(new \DateTime());
                $match->setLieu($tournoi->getLieu());
                $match->setPhase('tableau T'.$tour);
                $match->setDateCreation(new \DateTime());
                $em->persist($match);
                foreach ([['equipe' => $equipesTour[$i*2], 'role' => 'A'], ['equipe' => $equipesTour[$i*2+1], 'role' => 'B']] as $data) {
                    $me = new \App\Entity\MatchEquipe();
                    $me->setIdMatch($match);
                    $me->setIdEquipe($data['equipe']);
                    $me->setRole($data['role']);
                    $me->setDateCreation(new \DateTime());
                    $em->persist($me);
                }
            }
            // Préparer les qualifiés pour le tour suivant (à remplir après saisie des résultats)
            $equipesTour = array(); // Sera rempli après chaque tour
            // On arrête la boucle ici, la suite dépendra de la saisie des résultats
            break;
        }
        $em->flush();
        $this->addFlash('success', 'Tableau à élimination directe généré (premier tour). Saisis les résultats pour générer la suite.');
        return $this->redirectToRoute('tournoi_show', ['id' => $tournoi->getId()]);
    }

    #[Route('/tableau/{id}/generer-tour-suivant', name: 'tableau_generer_tour_suivant', methods: ['POST'])]
    public function genererTourSuivant(Request $request, \App\Entity\Tableau $tableau, EntityManagerInterface $em): Response
    {
        $matchs = $tableau->getMatchs()->toArray();
        $vainqueurs = [];
        foreach ($matchs as $match) {
            $res = $match->getResultat();
            if (!$res) {
                $this->addFlash('danger', 'Tous les résultats du tour doivent être saisis avant de générer le tour suivant.');
                return $this->redirectToRoute('tournoi_show', ['id' => $tableau->getIdTournoi()->getId()]);
            }
            $scoreA = $res->getScoreEquipe1();
            $scoreB = $res->getScoreEquipe2();
            $equipeA = null;
            $equipeB = null;
            foreach ($match->getMatchEquipes() as $me) {
                if ($me->getRole() === 'A') $equipeA = $me->getIdEquipe();
                if ($me->getRole() === 'B') $equipeB = $me->getIdEquipe();
            }
            if ($scoreA > $scoreB) {
                $vainqueurs[] = $equipeA;
            } elseif ($scoreB > $scoreA) {
                $vainqueurs[] = $equipeB;
            }
        }
        if (count($vainqueurs) < 2) {
            $this->addFlash('success', 'Le tournoi est terminé ! Vainqueur : ' . ($vainqueurs ? $vainqueurs[0]->getNom() : 'Aucun'));
            return $this->redirectToRoute('tournoi_show', ['id' => $tableau->getIdTournoi()->getId()]);
        }
        // Déterminer le nom du tour
        $n = count($vainqueurs);
        $nomTour = match($n) {
            16 => 'Huitièmes de finale',
            8 => 'Quarts de finale',
            4 => 'Demi-finale',
            2 => 'Finale',
            default => 'Tour suivant (' . $n . ' équipes)'
        };
        // Créer le nouveau tour dans le même tableau
        $tourNum = 1;
        $phases = array_map(fn($m) => $m->getPhase(), $tableau->getMatchs()->toArray());
        foreach ($phases as $phase) {
            if (preg_match('/T(\d+)/', $phase, $matches)) {
                $tourNum = max($tourNum, (int)$matches[1] + 1);
            }
        }
        $nbMatchs = intdiv(count($vainqueurs), 2);
        for ($i = 0; $i < $nbMatchs; $i++) {
            $match = new \App\Entity\Matchs();
            $match->setIdTableau($tableau);
            $match->setIdTournoi($tableau->getIdTournoi());
            $match->setDateHeure(new \DateTime());
            $match->setLieu($tableau->getIdTournoi()->getLieu());
            $match->setPhase($nomTour . ' (T' . $tourNum . ')');
            $match->setDateCreation(new \DateTime());
            $em->persist($match);
            foreach ([['equipe' => $vainqueurs[$i*2], 'role' => 'A'], ['equipe' => $vainqueurs[$i*2+1], 'role' => 'B']] as $data) {
                $me = new \App\Entity\MatchEquipe();
                $me->setIdMatch($match);
                $me->setIdEquipe($data['equipe']);
                $me->setRole($data['role']);
                $me->setDateCreation(new \DateTime());
                $em->persist($me);
            }
        }
        $em->flush();
        $this->addFlash('success', 'Tour suivant généré !');
        return $this->redirectToRoute('tournoi_show', ['id' => $tableau->getIdTournoi()->getId()]);
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

    #[Route('/{id}/stats', name: 'tournoi_stats', methods: ['GET'])]
    public function stats(Tournoi $tournoi): Response
    {
        // Vainqueur
        $vainqueur = null;
        foreach ($tournoi->getTableaus() as $tableau) {
            foreach ($tableau->getMatchs() as $match) {
                $phase = $match->getPhase();
                if ($phase && stripos($phase, 'finale') !== false && $match->getResultat()) {
                    $res = $match->getResultat();
                    $scoreA = $res->getScoreEquipe1();
                    $scoreB = $res->getScoreEquipe2();
                    $equipeA = null;
                    $equipeB = null;
                    foreach ($match->getMatchEquipes() as $me) {
                        if ($me->getRole() === 'A') $equipeA = $me->getIdEquipe();
                        if ($me->getRole() === 'B') $equipeB = $me->getIdEquipe();
                    }
                    if ($scoreA > $scoreB) $vainqueur = $equipeA;
                    elseif ($scoreB > $scoreA) $vainqueur = $equipeB;
                }
            }
        }
        // Classement général (sans points, mais avec fautes et cartons)
        $classement = [];
        foreach ($tournoi->getEquipesInscrites() as $equipe) {
            $buts = 0;
            $victoires = 0;
            $matchsJoues = 0;
            $fautes = 0;
            $jaunes = 0;
            $rouges = 0;
            foreach ($equipe->getMatchEquipes() as $me) {
                $match = $me->getIdMatch();
                $res = $match->getResultat();
                if ($res) {
                    $matchsJoues++;
                    if ($me->getRole() === 'A') {
                        $score = $res->getScoreEquipe1();
                        $scoreAdv = $res->getScoreEquipe2();
                        $fautes += $res->getFautesEquipe1();
                        $jaunes += $res->getCartonsJaunesEquipe1();
                        $rouges += $res->getCartonsRougesEquipe1();
                    } else {
                        $score = $res->getScoreEquipe2();
                        $scoreAdv = $res->getScoreEquipe1();
                        $fautes += $res->getFautesEquipe2();
                        $jaunes += $res->getCartonsJaunesEquipe2();
                        $rouges += $res->getCartonsRougesEquipe2();
                    }
                    if ($score > $scoreAdv) {
                        $victoires++;
                    }
                    $buts += $score;
                }
            }
            $classement[] = [
                'equipe' => $equipe,
                'victoires' => $victoires,
                'buts' => $buts,
                'matchs' => $matchsJoues,
                'fautes' => $fautes,
                'jaunes' => $jaunes,
                'rouges' => $rouges,
            ];
        }
        usort($classement, function($a, $b) {
            if ($a['victoires'] === $b['victoires']) {
                if ($a['buts'] === $b['buts']) {
                    return $a['fautes'] <=> $b['fautes'];
                }
                return $b['buts'] <=> $a['buts'];
            }
            return $b['victoires'] <=> $a['victoires'];
        });
        // Meilleurs buteurs (structure vide)
        $buteurs = [];
        foreach ($tournoi->getEquipesInscrites() as $equipe) {
            foreach ($equipe->getJoueurs() as $joueur) {
                $buts = 0;
                $buteurs[] = [
                    'joueur' => $joueur,
                    'buts' => $buts,
                ];
            }
        }
        return $this->render('tournoi/stats.html.twig', [
            'tournoi' => $tournoi,
            'vainqueur' => $vainqueur,
            'classement' => $classement,
            'buteurs' => $buteurs,
        ]);
    }
} 