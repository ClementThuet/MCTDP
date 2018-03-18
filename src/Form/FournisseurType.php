<?php

namespace App\Form;

use App\Entity\Fournisseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label'  => 'Nom : '))
            ->add('numTelephone', TextType::class, array('label'  => 'N° de téléphone : ','required' => false,))
            ->add('mail', TextType::class, array('label'  => 'Mail : ','required' => false,))
            ->add('siteWeb', TextType::class, array('label'  => 'Site web : ','required' => false,))
            ->add('adresse', TextType::class, array('label'  => 'Adresse : ','required' => false,))
            ->add('observations', TextAreaType::class, array('label'  => 'Observations :  ','required' => false, 'attr' => array('cols' => '40','rows' => '4')))
            ->add('Enregistrer',      SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Fournisseur::class,
        ]);
    }
}
