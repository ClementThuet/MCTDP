<?php

namespace App\Form;

use App\Entity\SeanceQG;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class EditSeanceQGType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, 
                    array('label'  => 'Date : ',
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd'
                        ,'required' => true))
            ->add('Enregistrer',      SubmitType::class);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => \App\Entity\SeanceQG::class,
        ]);
    }
}
