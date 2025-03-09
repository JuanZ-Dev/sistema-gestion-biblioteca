<?php

namespace App\Entity;

use App\Repository\EjemplarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EjemplarRepository::class)]
class Ejemplar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ejemplares')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Libro $libro = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $codigo = null;

    #[ORM\Column(length: 13, nullable: true)]
    private ?string $estado = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_adquisicion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibro(): ?Libro
    {
        return $this->libro;
    }

    public function setLibro(?Libro $libro): static
    {
        $this->libro = $libro;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): static
    {
        $this->codigo = $codigo;

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

    public function getFechaAdquisicion(): ?\DateTimeInterface
    {
        return $this->fecha_adquisicion;
    }

    public function setFechaAdquisicion(?\DateTimeInterface $fecha_adquisicion): static
    {
        $this->fecha_adquisicion = $fecha_adquisicion;

        return $this;
    }
}
