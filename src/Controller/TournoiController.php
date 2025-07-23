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
} 