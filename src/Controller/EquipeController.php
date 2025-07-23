<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipe')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'equipe_index', methods: ['GET'])]
    public function index(EquipeRepository $equipeRepository): Response
    {
        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'equipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($equipe);
            $em->flush();

            return $this->redirectToRoute('equipe_index');
        }

        return $this->render('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'equipe_show', methods: ['GET'])]
    public function show(Equipe $equipe): Response
    {
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('equipe_index');
        }

        return $this->render('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $equipe->getId(), $request->request->get('_token'))) {
            $em->remove($equipe);
            $em->flush();
        }

        return $this->redirectToRoute('equipe_index');
    }

    #[Route('/{id}/inscription-tournoi', name: 'equipe_inscription_tournoi', methods: ['GET', 'POST'])]
    public function inscriptionTournoi(Request $request, Equipe $equipe, EntityManagerInterface $em, \App\Repository\TournoiRepository $tournoiRepository): Response
    {
        // Récupérer les tournois auxquels l'équipe n'est pas déjà inscrite
        $tournoisDisponibles = $tournoiRepository->createQueryBuilder('t')
            ->where(':equipe NOT MEMBER OF t.equipesInscrites')
            ->setParameter('equipe', $equipe)
            ->getQuery()
            ->getResult();

        if ($request->isMethod('POST')) {
            $tournoiId = $request->request->get('tournoi_id');
            if ($tournoiId) {
                $tournoi = $tournoiRepository->find($tournoiId);
                if ($tournoi) {
                    $equipe->addTournoiInscrit($tournoi);
                    $em->persist($equipe);
                    $em->flush();
                    $this->addFlash('success', 'Inscription au tournoi réussie !');
                }
            }
            return $this->redirectToRoute('equipe_show', ['id' => $equipe->getId()]);
        }

        return $this->render('equipe/inscription_tournoi.html.twig', [
            'equipe' => $equipe,
            'tournois' => $tournoisDisponibles,
        ]);
    }
}
