<?php

namespace App\Form;

use App\Entity\Ejemplar;
use App\Entity\Libro;
use App\Entity\Prestamo;
use App\Entity\Usuario;
use App\Repository\LibroRepository;
use App\Repository\PrestamoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestamoType extends AbstractType
{
    public function __construct(
        private readonly UsuarioRepository $usuarioRepository,
        private readonly PrestamoRepository $prestamoRepository,
    )
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = ['ROLE_ALUMNO', 'ROLE_PROFESOR', 'ROLE_INVITADO'];
        $prestamoId = $builder->getData()->getId();
        $ejemplarId = $this->obtenerEjemplarSeleccionado($prestamoId);
        $usuarioId = $this->obtenerUsuarioSeleccionado($prestamoId);

        $builder
            ->add('libro', EntityType::class, [
                'class' => Libro::class,
                'choice_label' => 'titulo',
//                'choices' => $this->libroRepository->librosConEjemplaresDisponibles(),
                'query_builder' => function (LibroRepository $repository) use ($ejemplarId) {
                    return $repository->librosConEjemplaresDisponibles($ejemplarId);
                },
                'mapped' => false
            ])
            ->add('usuario', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'nombre',
                'query_builder' => function (UsuarioRepository $repository) use ($roles, $usuarioId) {
                    return $repository->usuariosPorRoles($roles, $usuarioId);
                }
//                'choices' => $this->usuarioRepository->usuariosPorRoles($roles),
            ])
            ->add('fecha_prestamo', null, [
                'widget' => 'single_text',
            ])
            ->add('fecha_devolucion_prevista', null, [
                'widget' => 'single_text',
            ])
            ->add('fecha_devolucion_real', null, [
                'widget' => 'single_text'
            ])
            ->add('estado', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prestamo::class,
        ]);
    }

    private function obtenerEjemplarSeleccionado(?int $prestamoId): array
    {
        return $this->prestamoRepository->idEjemplar($prestamoId);
    }

    private function obtenerUsuarioSeleccionado(?int $prestamoId): array
    {
        return $this->prestamoRepository->idUsuario($prestamoId);
    }
}
