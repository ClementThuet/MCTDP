<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EditPatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder->add('actif',  CheckboxType::class, array('label'  => 'Actif : '
           , 'attr' => array('style' => 'zoom:2.5;')
           , 'required' => false)); 
    }
    public function getParent()
    {
        return PatientType::class;
    }
}
