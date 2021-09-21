<?php

namespace App\Entity;

use App\Repository\PosicioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PosicioRepository::class)
 */
class Posicio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Jugador::class, mappedBy="Posicio")
     */
    private $jugadors;

    public function __construct()
    {
        $this->jugadors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Jugador[]
     */
    public function getJugadors(): Collection
    {
        return $this->jugadors;
    }

    public function addJugador(Jugador $jugador): self
    {
        if (!$this->jugadors->contains($jugador)) {
            $this->jugadors[] = $jugador;
            $jugador->setPosicio($this);
        }

        return $this;
    }

    public function removeJugador(Jugador $jugador): self
    {
        if ($this->jugadors->removeElement($jugador)) {
            // set the owning side to null (unless already changed)
            if ($jugador->getPosicio() === $this) {
                $jugador->setPosicio(null);
            }
        }

        return $this;
    }
}
