<?php

namespace App\Entity;

use App\Repository\CategorieCarteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategorieCarteRepository::class)
 */
class CategorieCarte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le description est requise")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     *  @Assert\NotBlank(message="Le prix est requise")
     * @Assert\Range(
     *      min = 100,
     *      max = 1000,
     *     notInRangeMessage ="Le prix doit etre entre 100 et 1000"
     * )
     */
    private $prix;

    /**
     * @ORM\Column(type="float")

     */
    private $montant_max;

    /**
     * @ORM\ManyToOne(targetEntity=Carte::class, inversedBy="categorieCartes")
     */
    private $carte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getMontantMax(): ?float
    {
        return $this->montant_max;
    }

    public function setMontantMax(float $montant_max): self
    {
        $this->montant_max = $montant_max;

        return $this;
    }

    public function getCarte(): ?Carte
    {
        return $this->carte;
    }

    public function setCarte(?Carte $carte): self
    {
        $this->carte = $carte;

        return $this;
    }
}