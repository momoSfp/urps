<?php

namespace App\Form;

use App\Entity\Tutor;
use App\Form\UserType;
use App\Form\UtilsType;
use App\Form\UserTutorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TutorType extends UtilsType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('postcode', TextType::class, $this->configuration("Code postal du cabinet", "Code postal du cabinet du médecin tuteur"))
            ->add('adeli', TextType::class, $this->configuration("Numéro ADELI", "ADELI du médecin tuteur", [
                "required" => false
            ])) 
            ->add('userRelation', UserTutorType::class)      
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tutor::class,
            'cascade_validation' => true,
        ]);
    }
}
