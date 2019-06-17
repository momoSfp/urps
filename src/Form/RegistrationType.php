<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Tutor;
use App\Form\UtilsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends UtilsType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, $this->configuration("Prénom *", "Votre prénom"))
            ->add('lastname', TextType::class, $this->configuration("Nom *", "Votre nom de famille"))
            ->add('age', ChoiceType::class, $this->configuration("Votre tranche d'âge", "Votre tranche d'âge", 
                [
                    'choices'  => [
                        'Aucune'  => NULL,
                        '- de 30' => '- de 30',
                        '30-39'   => '30-39',
                        '40-49'   => '40-49',
                        '50-59'   => '50-59',
                        '60-69'   => '60-69',
                        '70-79'   => '70-79',
                        '+ de 80' => '+ de 80'
                    ], 
                    'required'  => true,
                ]
            ))
            ->add('email', EmailType::class, $this->configuration("Email *", "Votre adresse email"))
            ->add('password', PasswordType::class, $this->configuration("Mot de passe *", "Veuillez choisir un mot de passe de 5 caractères minimum"))
            ->add('passwordConfirm', PasswordType::class, $this->configuration("Confirmation de mot de passe *", "Veuillez confirmer votre mot de passe"))
            ->add('tutor', EntityType::class,[
                'label' => "Médecin traitant *",
                'placeholder' => "Tapez ici les premières lettres du nom de votre médecin",
                'class' => Tutor::class,
                'choice_label' => function ($tutor) {
                    return $tutor->getUserRelation()->getTutorFullname() ;
                }
            ])
            ->add('termsAccepted', CheckboxType::class, [
                'mapped' => false,
                'constraints' => new IsTrue(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
