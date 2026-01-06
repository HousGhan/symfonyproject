<?php

namespace App\Form;

use App\Entity\MedicalRecord;
use App\Entity\Patient;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicalRecordType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('title', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ])
      ->add('description', TextAreaType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
          'style' => 'height: 200px'
        ],
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => MedicalRecord::class,
      'is_edit' => false,
      'attr' => [
        'novalidate' => 'novalidate',
      ]
    ]);
  }
}
