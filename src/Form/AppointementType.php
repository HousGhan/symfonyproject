<?php

namespace App\Form;

use App\Entity\Appointement;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointementType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('date', DateTimeType::class, [
        'attr' => [
          'class' => 'form-control',
          'placeholder' => ' ',
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Appointement::class,
      'is_edit' => false,
      'attr' => [
        'novalidate' => 'novalidate',
      ]
    ]);
  }
}
