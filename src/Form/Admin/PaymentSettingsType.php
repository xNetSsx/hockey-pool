<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\RuleSet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paymentAccountNumber', TextType::class, [
                'label' => 'Číslo účtu',
            ])
            ->add('paymentBankCode', TextType::class, [
                'label' => 'Kód banky',
            ])
            ->add('paymentAmount', NumberType::class, [
                'label' => 'Částka',
                'scale' => 2,
            ])
            ->add('paymentMessage', TextType::class, [
                'label' => 'Zpráva pro příjemce',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Uložit nastavení platby',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => RuleSet::class]);
    }
}
