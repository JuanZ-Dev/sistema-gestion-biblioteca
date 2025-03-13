<?php

namespace App\Entity;

use App\Repository\MultaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MultaRepository::class)]
class Multa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestamo $prestamo = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $monto = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 10)]
    private ?string $estado = null;

    public function __construct()
    {
        $this->fecha = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrestamo(): ?Prestamo
    {
        return $this->prestamo;
    }

    public function setPrestamo(Prestamo $prestamo): static
    {
        $this->prestamo = $prestamo;

        return $this;
    }

    public function getMonto(): ?string
    {
        return $this->monto;
    }

    public function setMonto(string $monto): static
    {
        $this->monto = $monto;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

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
