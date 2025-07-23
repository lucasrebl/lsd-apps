<?php

namespace App\Controller;

use App\Repository\EquipeRepository;
use App\Repository\JoueurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(EquipeRepository $equipeRepository, JoueurRepository $joueurRepository, EntityManagerInterface $em): Response
    {
        // Création des équipes par défaut si aucune équipe n'existe
        if (count($equipeRepository->findAll()) === 0) {
            $noms = [
                ['nom' => 'Barca', 'pays' => 'Espagne'],
                ['nom' => 'ASSE', 'pays' => 'France'],
                ['nom' => 'PSG', 'pays' => 'France'],
                ['nom' => 'OL', 'pays' => 'France'],
                ['nom' => 'Monaco', 'pays' => 'France'],
                ['nom' => 'RMadrid', 'pays' => 'Espagne'],
                ['nom' => 'OM', 'pays' => 'France'],
                ['nom' => 'Liverpool', 'pays' => 'Angleterre'],
            ];
            foreach ($noms as $i => $data) {
                $equipe = new \App\Entity\Equipe();
                $equipe->setNom($data['nom']);
                $equipe->setPays($data['pays']);
                $equipe->setNomCapitaine($data['nom'] . ' Capitaine');
                $equipe->setEmail(strtolower($data['nom']) . '@club.com');
                $equipe->setTelephone('000000000' . $i); // numéro unique
                $em->persist($equipe);
            }
            $em->flush();
        }
        return $this->render('home/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
            'joueurs' => $joueurRepository->findAll(),
        ]);
    }
} 