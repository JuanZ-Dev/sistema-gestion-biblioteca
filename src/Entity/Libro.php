<?php

namespace App\Entity;

use App\Repository\LibroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibroRepository::class)]
class Libro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titulo = null;

    #[ORM\Column(length: 14)]
    private ?string $isbn = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $anio_publicacion = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Editorial $editorial = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categoria $categoria = null;

    /**
     * @var Collection<int, Autor>
     */
    #[ORM\ManyToMany(targetEntity: Autor::class)]
    private Collection $autor;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $idioma = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    /**
     * @var Collection<int, Ejemplar>
     */
    #[ORM\OneToMany(targetEntity: Ejemplar::class, mappedBy: 'libro', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $ejemplares;

    public function __construct()
    {
        $this->autor = new ArrayCollection();
        $this->ejemplares = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getAnioPublicacion(): ?int
    {
        return $this->anio_publicacion;
    }

    public function setAnioPublicacion(?int $anio_publicacion): static
    {
        $this->anio_publicacion = $anio_publicacion;

        return $this;
    }

    public function getEditorial(): ?Editorial
    {
        return $this->editorial;
    }

    public function setEditorial(?Editorial $editorial): static
    {
        $this->editorial = $editorial;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * @return Collection<int, Autor>
     */
    public function getAutor(): Collection
    {
        return $this->autor;
    }

    public function addAutor(Autor $autor): static
    {
        if (!$this->autor->contains($autor)) {
            $this->autor->add($autor);
        }

        return $this;
    }

    public function removeAutor(Autor $autor): static
    {
        $this->autor->removeElement($autor);

        return $this;
    }

    public function getIdioma(): ?string
    {
        return $this->idioma;
    }

    public function setIdioma(?string $idioma): static
    {
        $this->idioma = $idioma;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection<int, Ejemplar>
     */
    public function getEjemplares(): Collection
    {
        return $this->ejemplares;
    }

    public function addEjemplare(Ejemplar $ejemplar): static
    {
        if (!$this->ejemplares->contains($ejemplar)) {
            $this->ejemplares->add($ejemplar);
            $ejemplar->setLibro($this);
        }

        return $this;
    }

    public function removeEjemplare(Ejemplar $ejemplar): static
    {
        if ($this->ejemplares->removeElement($ejemplar)) {
            // set the owning side to null (unless already changed)
            if ($ejemplar->getLibro() === $this) {
                $ejemplar->setLibro(null);
            }
        }

        return $this;
    }

//    public function getEstado(): string
//    {
//        foreach ($this->ejemplares as $ejemplar) {
//            return $ejemplar->getEstado() !== null ? 'disponible': 'pendiente';
//        }
//        return 'prestado';
//    }
}
