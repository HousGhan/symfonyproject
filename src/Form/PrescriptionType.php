<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\Prescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextAreaType;

class PrescriptionType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('medicaments', TextAreaType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
          'style' => 'height: 500px'
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Prescription::class,
      'is_edit' => false,
      'attr' => [
        'novalidate' => 'novalidate',
      ]
    ]);
  }
}
