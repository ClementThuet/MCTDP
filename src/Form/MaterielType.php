<?php

namespace App\Form;

use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label'  => 'Nom : '))
            ->add('categorie', EntityType::class, array(
                    'class'        => Categorie::class,
                    'query_builder' => function (CategorieRepository $er) {
                        return $er->findByNameASC('categorie');
                    },
                    'choice_label' => 'nom',
                    ))
            ->add('description', TextareaType::class, array('label'  => 'Description : ','required' => false))
            ->add('qteStock', IntegerType::class, array('label'  => 'Quantité en stock : ','required' => true))
            ->add('Enregistrer',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Materiel::class,
        ]);
    }
}
