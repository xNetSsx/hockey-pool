<?php

declare(strict_types=1);

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Uživatelské jméno'])
            ->add('email', EmailType::class, ['label' => 'Email', 'required' => false])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Heslo',
                'required' => $options['is_new'],
                'mapped' => false,
                'constraints' => $options['is_new'] ? [new Assert\NotBlank(), new Assert\Length(min: 4)] : [new Assert\Length(min: 4)],
                'attr' => ['placeholder' => $options['is_new'] ? '' : 'ponechte prázdné pro zachování'],
            ])
            ->add('admin', CheckboxType::class, [
                'label' => 'Administrátor (ROLE_ADMIN)',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['is_new' => false]);
    }
}
