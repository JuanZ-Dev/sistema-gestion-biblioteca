<?php

namespace App\Form;

use App\Entity\Multa;
use App\Entity\Prestamo;
use App\Repository\MultaRepository;
use App\Repository\PrestamoRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultaType extends AbstractType
{
    public function __construct(private readonly MultaRepository $multaRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $multaId = $builder->getData()->getId();
        $prestamoId = $this->obtenerPrestamoSeleccionado($multaId);

        $builder
            ->add('prestamo', EntityType::class, [
                'class' => Prestamo::class,
//                'choice_label' => 'usuario',
                'choice_attr' => function (Prestamo $prestamo, $key, $index) {
                    return [
                        'data-fecha-prevista' => $prestamo->getFechaDevolucionPrevista() ? $prestamo->getFechaDevolucionPrevista()->format('Y-m-d') : '',
                        'data-fecha-real' => $prestamo->getFechaDevolucionReal() ? $prestamo->getFechaDevolucionReal()->format('Y-m-d') : ''
                    ];
                },
                'query_builder' => function (PrestamoRepository $repository) use ($prestamoId) {
                    return $repository->prestamosRetrasados($prestamoId);
                }
            ])
            ->add('fecha', null, [
                'widget' => 'single_text',
            ])
            ->add('monto')
//            ->add('estado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Multa::class,
        ]);
    }

    private function obtenerPrestamoSeleccionado(?int $multaId): array
    {
        return $this->multaRepository->idPrestamo($multaId);
    }
}
