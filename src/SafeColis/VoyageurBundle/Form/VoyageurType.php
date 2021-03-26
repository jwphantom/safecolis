<?php

namespace SafeColis\VoyageurBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class VoyageurType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
     
      $builder

        ->add('villedepart',     TextType::class , [
          'attr' => ['placeholder' => 'Indiquez votre ville de départ']
      ])
        ->add('villearrive',     TextType::class , [
          'attr' => ['placeholder' => 'Indiquez votre ville d\'arrivé']
      ])
        ->add('termcondition',     CheckBoxType::class, [
            'label'    => 'Agree with terms and conditions',
            'required' => true
        ])
        ->add('justification',     JustificationType::class, [
          'attr' => ['placeholder' => 'Justificatif de voyage']
      ])
      ->add('identification',     IdentificationType::class, [
        'attr' => ['name' => 'Pièce d\'identité file']
    ])   
        

      ;
  
  
     }
  
    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults(array(
        'data_class' => 'SafeColis\VoyageurBundle\Entity\Voyageur'
      ));
    }



}
