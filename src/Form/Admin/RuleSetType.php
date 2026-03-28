<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\RuleSet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RuleSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('winnerBasePoints', NumberType::class, [
                'label' => 'Body za správného vítěze',
                'scale' => 2,
            ])
            ->add('wrongOpponentBonus', NumberType::class, [
                'label' => 'Bonus za špatný tip soupeře (×)',
                'scale' => 2,
            ])
            ->add('exactScoreBonus', NumberType::class, [
                'label' => 'Bonus za přesné skóre',
                'scale' => 2,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => RuleSet::class]);
    }
}
