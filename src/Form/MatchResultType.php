<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MatchResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('homeScore', IntegerType::class, [
                'label' => $options['home_team_label'],
                'constraints' => [
                    new Assert\NotNull(),
                    new Assert\PositiveOrZero(),
                ],
                'attr' => ['min' => 0, 'class' => 'w-20'],
            ])
            ->add('awayScore', IntegerType::class, [
                'label' => $options['away_team_label'],
                'constraints' => [
                    new Assert\NotNull(),
                    new Assert\PositiveOrZero(),
                ],
                'attr' => ['min' => 0, 'class' => 'w-20'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Uložit výsledek',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
            'home_team_label' => 'Domácí',
            'away_team_label' => 'Hosté',
        ]);
    }
}
