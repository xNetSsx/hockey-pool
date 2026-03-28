<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Tournament;
use App\Enum\TournamentPhase;
use App\Repository\TournamentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;

class BulkGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tournament', EntityType::class, [
                'class' => Tournament::class,
                'choice_label' => static fn (Tournament $t) => $t->getName() . ' (' . $t->getYear() . ')',
                'query_builder' => static fn (TournamentRepository $r) => $r->createQueryBuilder('t')->orderBy('t.year', 'DESC'),
                'label' => 'Turnaj',
            ])
            ->add('phase', EnumType::class, [
                'class' => TournamentPhase::class,
                'label' => 'Fáze',
                'choice_label' => static fn (TournamentPhase $p) => $p->label(),
            ])
            ->add('games', CollectionType::class, [
                'entry_type' => BulkGameRowType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ]);
    }
}
