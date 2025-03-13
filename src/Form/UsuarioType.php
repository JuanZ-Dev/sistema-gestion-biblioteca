<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('passwordActual', PasswordType::class, [
                'required' => false,
                'label' => 'Password Actual',
            ])
            ->add('password', PasswordType::class, [
                'required' => false,
                'label' => 'Password Nuevo',
            ])
            ->add('nombre', TextType::class)
            ->add('email', EmailType::class)
            ->add('telefono', TextType::class)
            ->add('direccion', TextType::class)
            ->add('fechaRegistro', DateType::class)
            ->add('estado', ChoiceType::class, [
                'choices' => [
                    'Activo' => 'activo',
                    'Suspendido' => 'suspendido',
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrador' => 'ROLE_ADMIN',
                    'Empleado' => 'ROLE_EMPLEADO',
                    'Profesor' => 'ROLE_PROFESOR',
                    'Alumno' => 'ROLE_ALUMNO',
                    'Invitado' => 'ROLE_INVITADO',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
