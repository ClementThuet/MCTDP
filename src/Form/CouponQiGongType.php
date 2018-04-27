<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponQiGongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patient', EntityType::class, array(
            'class'        => Patient::class,
            'choice_label' => 'nom',

            'query_builder' => function(\App\Repository\PatientRepository $repository) 
            {
                return $repository->getOrderQueryBuilder();
            }))
            ->add('observations', TextAreaType::class, array('label'  => 'Observations :  ','required' => false, 'attr' => array('cols' => '40','rows' => '4')))
            ->add('Enregistrer',SubmitType::class);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => \App\Entity\CouponQiGong::class,
        ]);
    }
}
