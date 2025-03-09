<?php

namespace App\Form;

use App\Entity\Ejemplar;
use App\Entity\Libro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

class EjemplarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codigo', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ingrese el número del ejemplar'
                ],
                'constraints' => [
                    new Regex(['pattern' => '/^(LIB-[1-9]\d*-(?:00[1-9]|0[1-9]\d|[1-9]\d{2})|[1-9]\d{0,2})$/'])
                ]
            ])
            ->add('estado', HiddenType::class)
            ->add('fecha_adquisicion', null, [
                'widget' => 'single_text',
            ])
//            ->add('libro', EntityType::class, [
//                'class' => Libro::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ejemplar::class,
        ]);
    }
}
