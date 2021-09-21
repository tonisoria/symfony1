<?php

namespace App\Entity;

use App\Repository\JugadorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=JugadorRepository::class)
 */
class Jugador
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $sobrenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $equip;

    /**
     * @ORM\ManyToOne(targetEntity=Posicio::class, inversedBy="jugadors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Posicio;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imatge;

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

    public function getSobrenom(): ?string
    {
        return $this->sobrenom;
    }

    public function setSobrenom(string $sobrenom): self
    {
        $this->sobrenom = $sobrenom;

        return $this;
    }

    public function getEquip(): ?string
    {
        return $this->equip;
    }

    public function setEquip(string $equip): self
    {
        $this->equip = $equip;

        return $this;
    }

    public function getPosicio(): ?Posicio
    {
        return $this->Posicio;
    }

    public function setPosicio(?Posicio $Posicio): self
    {
        $this->Posicio = $Posicio;

        return $this;
    }

    public function getImatge(): ?string
    {
        return $this->imatge;
    }

    public function setImatge(string $imatge): self
    {
        $this->imatge = $imatge;

        return $this;
    }
}
