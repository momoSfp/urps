<?php

namespace App\Form;

use App\Entity\Content;
use App\Form\ImageType;
use App\Form\UtilsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ContentType extends UtilsType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->configuration('Titre', 'Titre du serious game'))
            ->add('slug', TextType::class, $this->configuration('Lien de la page', 'Lien de la page (optionnel)', [ 'required' => false ]))
            ->add('description', TextType::class, $this->configuration('Description', 'Une description global du serious game'))
            ->add('content', TextareaType::class, $this->configuration('Contenu', 'Une description détaillé du serious game'))
            ->add('coverImage', UrlType::class, $this->configuration('Image de couverture', 'Une url d\'image'))
            ->add('gameFile', FileType::class)
            ->add('active', CheckboxType::class, $this->configuration('Activer Le serious game', 'test', [ 'required' => false ]))
            ->add('public', CheckboxType::class, $this->configuration('Rendre public', 'test', [ 'required' => false ]))
            ->add('images', CollectionType::Class, [
                'entry_type' => ImageType::Class,
                'allow_add'  => true,
                'allow_delete'  => true
            ])           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}