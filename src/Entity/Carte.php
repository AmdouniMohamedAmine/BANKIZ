<?php

namespace App\Entity;

use App\Repository\CarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CarteRepository::class)
 */
class Carte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $idclient;

    /**
     * @ORM\Column(type="date")
     */
    private $date_ex;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min = 3, max = 20, minMessage = "Merci de Vérifier Votre mot de passe")
     * @Assert\NotBlank(message="Le champs mot de paase est obligatoire * ")
     */
    private $mp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min = 3, max = 20, minMessage = "Merci de Vérifier Votre login")
     * @Assert\NotBlank(message="Le champs login est obligatoire * ")
     */
    private $login;

    /**
     * @ORM\Column(type="integer")
     */
    private $num_carte;

    /**
     * @ORM\OneToMany(targetEntity=CategorieCarte::class, mappedBy="carte")
     */
    private $categorieCartes;

    public function __construct()
    {
        $this->categorieCartes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdclient(): ?float
    {
        return $this->idclient;
    }

    public function setIdclient(float $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }

    public function getDateEx(): ?\DateTimeInterface
    {
        return $this->date_ex;
    }

    public function setDateEx(\DateTimeInterface $date_ex): self
    {
        $this->date_ex = $date_ex;

        return $this;
    }

    public function getMp(): ?string
    {
        return $this->mp;
    }

    public function setMp(string $mp): self
    {
        $this->mp = $mp;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getNumCarte(): ?int
    {
        return $this->num_carte;
    }

    public function setNumCarte(int $num_carte): self
    {
        $this->num_carte = $num_carte;

        return $this;
    }

    /**
     * @return Collection<int, CategorieCarte>
     */
    public function getCategorieCartes(): Collection
    {
        return $this->categorieCartes;
    }

    public function addCategorieCarte(CategorieCarte $categorieCarte): self
    {
        if (!$this->categorieCartes->contains($categorieCarte)) {
            $this->categorieCartes[] = $categorieCarte;
            $categorieCarte->setCarte($this);
        }

        return $this;
    }

    public function removeCategorieCarte(CategorieCarte $categorieCarte): self
    {
        if ($this->categorieCartes->removeElement($categorieCarte)) {
            // set the owning side to null (unless already changed)
            if ($categorieCarte->getCarte() === $this) {
                $categorieCarte->setCarte(null);
            }
        }

        return $this;
    }
}