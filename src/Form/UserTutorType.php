<?php

namespace App\Form;

use App\Entity\User;
use App\Form\UtilsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserTutorType extends UtilsType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, $this->configuration("Prénom", "Prénom du médecin tuteur"))
            ->add('lastname', TextType::class, $this->configuration("Nom", "Nom du médecin tuteur"))
            ->add('email', EmailType::class, $this->configuration("Email", "Email du médecin tuteur"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class           
        ]);
    }
}
