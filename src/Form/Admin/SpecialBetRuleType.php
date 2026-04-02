<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\SpecialBetRule;
use App\Entity\Tournament;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;
use App\Repository\TournamentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialBetRuleType extends AbstractType
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
            ->add('name', TextType::class, ['label' => 'Název pravidla'])
            ->add('valueType', EnumType::class, [
                'class' => BetValueType::class,
                'label' => 'Typ hodnoty',
                'choice_label' => static fn (BetValueType $t) => $t->label(),
            ])
            ->add('scoringType', EnumType::class, [
                'class' => BetScoringType::class,
                'label' => 'Typ bodování',
                'choice_label' => static fn (BetScoringType $t) => $t->label(),
            ])
            ->add('points', NumberType::class, ['label' => 'Body za výhru', 'scale' => 2])
            ->add('sortOrder', IntegerType::class, ['label' => 'Pořadí zobrazení'])
            ->add('isMedalRule', CheckboxType::class, [
                'label' => 'Medalové pravidlo (zobrazit na dashboardu)',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => SpecialBetRule::class]);
    }
}
