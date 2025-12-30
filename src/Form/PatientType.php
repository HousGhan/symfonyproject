<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PatientType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
      ->add('lastName', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
      ->add('cin', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Patient::class,
      'is_edit' => false,
      'attr' => [
        'novalidate' => 'novalidate',
      ]
    ]);

    $resolver->setAllowedTypes('is_edit', 'bool');
  }
}
