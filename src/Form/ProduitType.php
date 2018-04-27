<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Categorie;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label'  => 'Nom : '))
            ->add('categorie', EntityType::class, array(
                    'class'        => Categorie::class,
                    'choice_label' => 'nom',
                'label'  => 'Catégorie : '))
            ->add('posologie', TextType::class, array('label'  => 'Posologie : ','required' => false))
            ->add('fonction', TextareaType::class, array('label'  => 'Fonction : ','required' => false, 'attr' => array('cols' => '40','rows' => '4')))
            ->add('obsolete', CheckboxType::class, array('label'  => 'Obsolète : ','required' => false, 'attr' => array('style' => 'zoom:2.5;')))
            ->add('Enregistrer',SubmitType::class);
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Produit::class,
        ]);
    }
}
