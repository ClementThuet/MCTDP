<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom', TextType::class, array('label'  => 'Nom : '))
            ->add('Prenom', TextType::class, array('label'  => 'Prénom : ','required' => false))
            ->add('dateNaiss', DateType::class, 
                    array('label'  => 'Date de naissance : ',
              'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
                        ,'required' => false))
            ->add('Adresse', TextType::class, array('label'  => 'Adresse : ','required' => false))
            ->add('CP', TextType::class, array('label'  => 'Code postal : ','required' => false))
            ->add('Ville', TextType::class, array('label'  => 'Ville : ','required' => false))  
            ->add('Telephone', TextType::class, array('label'  => 'Téléphone : ','required' => false))
            ->add('Mail', TextType::class, array('label'  => 'Mail : ','required' => false))
            ->add('sitFam', TextType::class, array('label'  => 'Situation familiale : ','required' => false))
            ->add('Profession', TextType::class, array('label'  => 'Profession : ','required' => false))
            ->add('nbEnfant', IntegerType::class, array('label'  => 'Nombre d\'enfant : ','required' => false))
            ->add('accouchement', TextType::class, array('label'  => 'Accouchement : ','required' => false))
            ->add('typeHabitat', TextType::class, array('label'  => 'Type d\'habitat : ','required' => false))           
            ->add('allergies', TextType::class, array('label'  => 'Allergie(s) : ','required' => false))
            ->add('traitementEnCours', TextareaType::class, array('label'  => 'Traitement en cours : ','required' => false, 'attr' => array('cols' => '40','rows' => '4')))
            ->add('atcdChirurgical', TextareaType::class, array('label'  => 'ATCD chirurgicaux : ','required' => false, 'attr' => array('cols' => '40','rows' => '4')))
            ->add('atcdFamiliaux', TextareaType::class, array('label'  => 'ATCD familiaux : ','required' => false, 'attr' => array('cols' => '40','rows' => '4')))
            ->add('atcdMedical', TextareaType::class, array('label'  => 'ATCD médicaux : ','required' => false, 'attr' => array('cols' => '40','rows' => '4')))
            ->add('contraception', TextType::class, array('label'  => 'Contraception : ','required' => false))
            ->add('observations', TextAreaType::class, array('label'  => 'Observations : ','required' => false, 'attr' => array('cols' => '40','rows' => '4', 'wrap'=>'hard')))
            ->add('accepteMedNonTradi',  CheckboxType::class, array('label'  => 'Patient accepte la pratique d\'une médecine non traditionnelle : ', 'attr' => array('style' => 'zoom:2.5;'), 'required' => false))
            ->add('accepteAcup', CheckboxType::class, array('label'  => 'Le patient accepte le traitement par acupuncture  : ', 'attr' => array('style' => 'zoom:2.5;'),'required' => false))
            ->add('Enregistrer',      SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => Patient::class,
        ]);
    }
}
