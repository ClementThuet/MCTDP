<?php

namespace App\Form;

use App\Entity\Reglement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReglementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule', TextType::class, array('label'  => 'Intitulé : ','required' => false))
            ->add('montant', NumberType::class, array('label'  => 'Montant : '))
            ->add('nomBanque', TextType::class, array('label'  => 'Nom de la banque : '))
            ->add('numCheque', IntegerType::class, array('label'  => 'Numéro de chèque : '))
            ->add('date', DateType::class, 
                    array('label'  => 'Date : ',
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd'
                        ,'required' => true))
            ->add('encaisse', CheckboxType::class, array('label'  => 'Chèque encaissé : ' ,'required' => false))
             ->add('Enregistrer',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Reglement::class,
        ]);
    }
}
