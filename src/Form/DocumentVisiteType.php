<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentVisiteType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder   
        ->add('file', FileType::class, array('label'  => 'Document : ', 'attr' => 
            array('cols' => '4','rows' => '4','style' => 'margin-left:20px;margin-bottom:5px;')))
        ->add('observations', TextareaType::class, array('label'  => 'Observations : ','required' => false,
            'attr' => array('style' => 'width:30vw;height:15vh;margin-left:20px;margin-top:1vh;')));
  }
  
   public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Document::class,
        ));
    }
}