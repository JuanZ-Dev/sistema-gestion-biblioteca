<?php

namespace App\Entity;

use App\Repository\PrestamoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestamoRepository::class)]
class Prestamo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ejemplar $ejemplar = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha_prestamo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha_devolucion_prevista = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_devolucion_real = null;

    #[ORM\Column(length: 10)]
    private ?string $estado = null;

    public function __construct()
    {
        $this->fecha_prestamo = new \DateTime();
        $this->fecha_devolucion_prevista = (new \DateTime())->modify('+7 days');
    }

    public function __toString(): string
    {
        return $this->usuario->getNombre() . ' - ' . $this->ejemplar->getLibro()->getTitulo();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEjemplar(): ?Ejemplar
    {
        return $this->ejemplar;
    }

    public function setEjemplar(?Ejemplar $ejemplar): static
    {
        $this->ejemplar = $ejemplar;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getFechaPrestamo(): ?\DateTimeInterface
    {
        return $this->fecha_prestamo;
    }

    public function setFechaPrestamo(\DateTimeInterface $fecha_prestamo): static
    {
        $this->fecha_prestamo = $fecha_prestamo;

        return $this;
    }

    public function getFechaDevolucionPrevista(): ?\DateTimeInterface
    {
        return $this->fecha_devolucion_prevista;
    }

    public function setFechaDevolucionPrevista(\DateTimeInterface $fecha_devolucion_prevista): static
    {
        $this->fecha_devolucion_prevista = $fecha_devolucion_prevista;

        return $this;
    }

    public function getFechaDevolucionReal(): ?\DateTimeInterface
    {
        return $this->fecha_devolucion_real;
    }

    public function setFechaDevolucionReal(?\DateTimeInterface $fecha_devolucion_real): static
    {
        $this->fecha_devolucion_real = $fecha_devolucion_real;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }
}
