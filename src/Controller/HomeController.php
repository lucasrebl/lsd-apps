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
    private function insertDefaultJoueurs(EntityManagerInterface $em): void
    {
        $equipeRepo = $em->getRepository(\App\Entity\Equipe::class);
        $equipes = $equipeRepo->findAll();
        $joueursParEquipe = [
            [
                ['nom' => 'Dupont', 'prenom' => 'Lucas', 'poste' => 'gardien'],
                ['nom' => 'Martin', 'prenom' => 'Alexandre', 'poste' => 'défenseur'],
                ['nom' => 'Bernard', 'prenom' => 'Julien', 'poste' => 'attaquant'],
            ],
            [
                ['nom' => 'Durand', 'prenom' => 'Sophie', 'poste' => 'gardien'],
                ['nom' => 'Petit', 'prenom' => 'Nicolas', 'poste' => 'défenseur'],
                ['nom' => 'Leroy', 'prenom' => 'Camille', 'poste' => 'attaquant'],
            ],
            [
                ['nom' => 'Moreau', 'prenom' => 'Thomas', 'poste' => 'gardien'],
                ['nom' => 'Simon', 'prenom' => 'Emma', 'poste' => 'défenseur'],
                ['nom' => 'Laurent', 'prenom' => 'Hugo', 'poste' => 'attaquant'],
            ],
            [
                ['nom' => 'Garcia', 'prenom' => 'Léa', 'poste' => 'gardien'],
                ['nom' => 'Roux', 'prenom' => 'Louis', 'poste' => 'défenseur'],
                ['nom' => 'Fournier', 'prenom' => 'Chloé', 'poste' => 'attaquant'],
            ],
            [
                ['nom' => 'Girard', 'prenom' => 'Paul', 'poste' => 'gardien'],
                ['nom' => 'Andre', 'prenom' => 'Manon', 'poste' => 'défenseur'],
                ['nom' => 'Mercier', 'prenom' => 'Lucas', 'poste' => 'attaquant'],
            ],
            [
                ['nom' => 'Blanc', 'prenom' => 'Sarah', 'poste' => 'gardien'],
                ['nom' => 'Guerin', 'prenom' => 'Maxime', 'poste' => 'défenseur'],
                ['nom' => 'Boyer', 'prenom' => 'Jules', 'poste' => 'attaquant'],
            ],
            [
                ['nom' => 'Henry', 'prenom' => 'Eva', 'poste' => 'gardien'],
                ['nom' => 'Roussel', 'prenom' => 'Noah', 'poste' => 'défenseur'],
                ['nom' => 'Nicolas', 'prenom' => 'Lina', 'poste' => 'attaquant'],
            ],
            [
                ['nom' => 'Morin', 'prenom' => 'Arthur', 'poste' => 'gardien'],
                ['nom' => 'Mathieu', 'prenom' => 'Anna', 'poste' => 'défenseur'],
                ['nom' => 'Clement', 'prenom' => 'Tom', 'poste' => 'attaquant'],
            ],
        ];
        foreach ($equipes as $idx => $equipe) {
            if ($equipe->getJoueurs()->count() === 0) {
                $joueurs = $joueursParEquipe[$idx % count($joueursParEquipe)];
                foreach ($joueurs as $np) {
                    $joueur = new \App\Entity\Joueur();
                    $joueur->setNom($np['nom']);
                    $joueur->setPrenom($np['prenom']);
                    $joueur->setPoste($np['poste']);
                    $joueur->setIdEquipe($equipe);
                    $joueur->setDateCreation(new \DateTime());
                    $em->persist($joueur);
                }
            }
        }
        $em->flush();
    }

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
        // Ajout des joueurs par équipe si besoin
        $this->insertDefaultJoueurs($em);
        return $this->render('home/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
            'joueurs' => $joueurRepository->findAll(),
        ]);
    }
} 