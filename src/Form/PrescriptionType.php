<?php

namespace App\Form;

use App\Entity\Prescription;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label'  => 'Nom : '))
            ->add('date', DateType::class, 
            array('label'  => 'Date : ','widget' => 'single_text','format' => 'yyyy-MM-dd','required' => true))
           /* ->add('produits', EntityType::class, array(
                    'class'        => Produit::class,
                    'choice_label' => 'nom'))*/
            ->add('observations', TextareaType::class, array('label'  => 'Observations :  ','required' => false, 'attr' => array('cols' => '40','rows' => '4')))
            ->add('Enregistrer',      SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Prescription::class,
        ]);
    }
}
