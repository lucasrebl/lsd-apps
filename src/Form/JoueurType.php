<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Equipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('poste', TextType::class, [
                'label' => 'Poste',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('id_equipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom',
                'label' => 'Équipe',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionner une équipe',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
} 