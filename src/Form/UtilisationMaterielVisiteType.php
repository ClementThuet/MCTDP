<?php

namespace App\Form;

use App\Entity\UtilisationMaterielVisite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UtilisationMaterielVisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numLot', TextType::class, array('label'  => '>Numéro de lot : '))
            ->add('quantite', IntegerType::class, array('label'  => 'Quantité : '))
            ->add('Enregistrer',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => UtilisationMaterielVisite::class,
        ]);
    }
}
