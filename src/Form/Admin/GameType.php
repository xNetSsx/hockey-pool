<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Enum\TournamentPhase;
use App\Repository\TeamRepository;
use App\Repository\TournamentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $teamOptions = [
            'class' => Team::class,
            'choice_label' => static fn (Team $t) => $t->getFullLabel(),
            'query_builder' => static fn (TeamRepository $r) => $r->createQueryBuilder('t')->orderBy('t.name', 'ASC'),
            'placeholder' => '— Vyber tým —',
        ];

        $builder
            ->add('tournament', EntityType::class, [
                'class' => Tournament::class,
                'choice_label' => static fn (Tournament $t) => $t->getName() . ' (' . $t->getYear() . ')',
                'query_builder' => static fn (TournamentRepository $r) => $r->createQueryBuilder('t')->orderBy('t.year', 'DESC'),
                'label' => 'Turnaj',
            ])
            ->add('homeTeam', EntityType::class, array_merge($teamOptions, ['label' => 'Domácí']))
            ->add('awayTeam', EntityType::class, array_merge($teamOptions, ['label' => 'Hosté']))
            ->add('phase', EnumType::class, [
                'class' => TournamentPhase::class,
                'label' => 'Fáze',
                'choice_label' => static fn (TournamentPhase $p) => $p->label(),
            ])
            ->add('playedAt', DateTimeType::class, [
                'label' => 'Datum a čas',
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Game::class]);
    }
}
