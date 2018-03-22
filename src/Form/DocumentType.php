<?php
// src/OC/PlatformBundle/Form/ImageType.php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder   
      ->add('intitule', TextType::class, array('label'  => 'Intitulé : '))
      ->add('date', DateType::class,array('label'  => 'Date : ',
      'widget' => 'single_text',
      'format' => 'yyyy-MM-dd'
      ,'required' => false))
      ->add('observations', TextAreaType::class, array('label'  => 'Observations : ','required' => false))
      ->add('file', FileType::class, array('label'  => 'Document : '))
      ->add('Enregistrer',      SubmitType::class);
  }
  
   public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Document::class,
        ));
    }
}