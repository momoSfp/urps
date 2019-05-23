<?php

namespace App\Form;

use App\Entity\Content;
use App\Form\ImageType;
use App\Form\UtilsType;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
            ->add('description', CKEditorType::class, array(
                    'required' => true,
                    'config' => array(
                        'toolbar' => array(
                            array(
                                'Undo', 'Redo', '-', 
                                'Cut', 'Copy', 'Paste', '-', 
                                'Link', '-', 
                                'Bold', 'Italic', 'Strike', 'RemoveFormat', '-',
                                'NumberedList', 'BulletedList', 'Outdent', 'Indent', '-',
                                'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', '-',
                                'Maximize'
                            )
                        )
                    )            
                )
            )
            ->add('question', TextType::class, $this->configuration('Question de recommandation', 'La question qui sera posé a l\'utilisateur pour lui recommandé le jeu ou non'))
            ->add('gameFile', FileType::class, $this->configuration('Fichier contentant le serious game', 'Le fichier au format zip contenant le serious game'))
            ->add('coverImageFile', FileType::class, $this->configuration('Image de présentation', 'l\'image qui sera utilisé comme image de couverture'))
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
