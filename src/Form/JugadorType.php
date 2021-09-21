<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

use App\Entity\Posicio;

class JugadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('posicio', EntityType::class, array('class' => Posicio::class,
            'choice_label' => 'nom'))
            ->add('nom', TextType::class)
            ->add('sobrenom', TextType::class)
            ->add('equip', TextType::class)
            ->add('imatge', FileType::class, [
                'label' => 'Imatge',
                'mapped' => false, 
                'required' => false, 
                'constraints' => [
                    new File([
                        'maxSize' => '3072k',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'El format de l\'imatge no és correcte.'
                    ])
                ],
            ])
            //->add('save', SubmitType::class, array('label' => 'Crear Jugador'))
            ->add('save', SubmitType::class, array('label' => $options['submit']))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'submit' => 'Enviar',
        ]);
    }
}