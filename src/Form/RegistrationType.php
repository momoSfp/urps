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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends UtilsType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, $this->configuration("Prénom", "Votre prénom"))
            ->add('lastname', TextType::class, $this->configuration("Nom", "Votre nom de famille"))
            ->add('email', EmailType::class, $this->configuration("Email", "Votre adresse email"))
            ->add('password', PasswordType::class, $this->configuration("Mot de passe", "Choississez un bon mot de passe !"))
            ->add('passwordConfirm', PasswordType::class, $this->configuration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe"))
            ->add('tutor', EntityType::class,[
                'label' => "Médecin traitant",
                'placeholder' => "choissisez votre médecin traitant parmi cette liste",
                'class' => Tutor::class,
                'choice_label' => function ($tutor) {
                    return $tutor->getUserRelation()->getTutorFullname() ;
                }
            ])
            ->add('termsAccepted', CheckboxType::class, [
                'label' => "j'ai lu et j'accepte les termes et conditions",
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
