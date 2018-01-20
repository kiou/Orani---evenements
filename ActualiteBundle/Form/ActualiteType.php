<?php

namespace ActualiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use GlobalBundle\Form\Type\LangueType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use ReferencementBundle\Form\ReferencementType;

class ActualiteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('slug', TextType::class)
            ->add('debut', DateType::class, array(
                    'attr' => array(
                        'placeholder' => '00/00/0000'
                    ),
                    'widget' => 'single_text',
                    'html5' => false,
                    'format' => 'dd/MM/yyyy'
                )
            )
            ->add('fileimage', FileType::class)
            ->add('categorie', EntityType::class, array(
                    'class' => 'ActualiteBundle:Categorie',
                    'choice_label' => 'nom',
                    'placeholder' => 'Choisir une catégorie'
                )
            )
            ->add('avant', ChoiceType::class,array(
                    'choices' => array(
                        'Oui' => true,
                        'Non' => False
                    ),
                    'expanded' => true
                )
            )
            ->add('resume', TextareaType::class)
            ->add('contenu', TextareaType::class)
            ->add('referencement', ReferencementType::class)
            ->add('langue', LangueType::class)
            ->add('Enregistrer', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ActualiteBundle\Entity\Actualite'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'actualitebundle_actualite';
    }


}
