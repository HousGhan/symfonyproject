<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SettingsType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('limitAppointements', IntegerType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
      ->add('phone', TelType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
      ->add('doctor', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
      ->add('cabinet', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
      ->add('email', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
      ->add('specialty', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
      ->add('address', TextareaType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
          'style' => 'height: 100px;',
        ],
      ]);
  }
  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Settings::class,
      'attr' => [
        'novalidate' => 'novalidate',
      ]
    ]);
  }
}
