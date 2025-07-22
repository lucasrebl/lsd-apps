<?php

namespace App\Controller;

use App\Repository\EquipeRepository;
use App\Repository\JoueurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(EquipeRepository $equipeRepository, JoueurRepository $joueurRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
            'joueurs' => $joueurRepository->findAll(),
        ]);
    }
} 