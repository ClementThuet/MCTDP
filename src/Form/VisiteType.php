<?php

namespace App\Form;

use App\Entity\Visite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motif', TextType::class, array('label'  => 'Motif : '))
            ->add('date', DateType::class, 
                    array('label'  => 'Date : ',
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd'
                        ,'required' => true,
    ))
            ->add('observations', TextAreaType::class, array('label'  => 'Observations :  ','required' => false, 'attr' => array('cols' => '40','rows' => '4')))
            ->add('Enregistrer',      SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Visite::class,
        ]);
    }
}
