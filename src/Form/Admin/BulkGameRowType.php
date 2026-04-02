<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

class BulkGameRowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $teamOptions = [
            'class' => Team::class,
            'choice_label' => static fn (Team $t) => $t->getLabel(),
            'query_builder' => static fn (TeamRepository $r) => $r->createQueryBuilder('t')->orderBy('t.name', 'ASC'),
            'placeholder' => '—',
        ];

        $builder
            ->add('homeTeam', EntityType::class, array_merge($teamOptions, ['label' => 'Domácí']))
            ->add('awayTeam', EntityType::class, array_merge($teamOptions, ['label' => 'Hosté']))
            ->add('playedAt', DateTimeType::class, [
                'label' => 'Datum a čas',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ]);
    }
}
