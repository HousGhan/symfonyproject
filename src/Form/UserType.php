<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email', EmailType::class, [
        "attr" => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ]
      ])
      ->add('roles', ChoiceType::class, [
        "choices" => [
          "SECRETARY" => "ROLE_SECRETARY",
          "DOCTOR" => "ROLE_DOCTOR",
        ],
        "multiple" => true,
        "attr" => [
          'class' => 'form-select',
          'placeholder' => ' ',
        ]
      ])
      ->add('password', PasswordType::class, [
        "attr" => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ]
      ])
      ->add('firstName', TextType::class, [
        "attr" => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ]
      ])
      ->add('lastName', TextType::class, [
        "attr" => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ]
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
      'is_edit' => false,
      'attr' => [
        'novalidate' => 'novalidate',
      ]
    ]);
  }
}
