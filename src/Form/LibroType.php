<?php

namespace App\Form;

use App\Entity\Autor;
use App\Entity\Categoria;
use App\Entity\Editorial;
use App\Entity\Libro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo')
            ->add('isbn')
            ->add('anio_publicacion')
            ->add('idioma')
            ->add('descripcion')
            ->add('editorial', EntityType::class, [
                'class' => Editorial::class,
                'choice_label' => 'nombre',
            ])
            ->add('categoria', EntityType::class, [
                'class' => Categoria::class,
                'choice_label' => 'nombre',
            ])
            ->add('autor', EntityType::class, [
                'class' => Autor::class,
                'choice_label' => 'nombre',
                'multiple' => true,
            ])
            ->add('ejemplares', CollectionType::class, [
                'entry_type' => EjemplarType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Libro::class,
        ]);
    }
}
