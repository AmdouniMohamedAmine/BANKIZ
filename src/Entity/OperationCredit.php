<?php

namespace App\Entity;

use App\Repository\OperationCreditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;
/**
 * @ORM\Entity(repositoryClass=OperationCreditRepository::class)
 */
class OperationCredit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateOp;

    /**
     * @ORM\Column(type="integer")
     */

    private $montPayer;

    /**
     * @ORM\Column(type="date")
     */
    private $echeance;

    /**
     * @ORM\Column(type="integer")
     */
    private $tauxInteret;

    /**
     * @ORM\Column(type="integer",)
     *  @ORM\GeneratedValue(strategy="AUTO")
     */
    private $solvabilite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeOperation;

    /**
     * @ORM\OneToMany(targetEntity=Credit::class, mappedBy="operationCredit")
     */
    private $Credit;


    public function __construct()
    {
        $this->solvabilite     = 1;
        $this->Credit = new ArrayCollection();


    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOp(): ?\DateTimeInterface
    {
        return $this->dateOp;
    }

    public function setDateOp(\DateTimeInterface $dateOp): self
    {
        $this->dateOp = $dateOp;

        return $this;
    }

    public function getMontPayer(): ?int
    {
        return $this->montPayer;
    }

    public function setMontPayer(int $montPayer): self
    {
        $this->montPayer = $montPayer;

        return $this;
    }

    public function getEcheance(): ?\DateTimeInterface
    {
        return $this->echeance;
    }

    public function setEcheance(\DateTimeInterface $echeance): self
    {
        $this->echeance = $echeance;

        return $this;
    }

    public function getTauxInteret(): ?int
    {
        return $this->tauxInteret;
    }

    public function setTauxInteret(int $tauxInteret): self
    {
        $this->tauxInteret = $tauxInteret;

        return $this;
    }

    public function getSolvabilite(): ?int
    {
        return $this->solvabilite;
    }

    public function setSolvabilite(int $solvabilite): self
    {
        $this->solvabilite = $solvabilite;

        return $this;
    }

    public function getTypeOperation(): ?string
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(string $typeOperation): self
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    /**
     * @return Collection<int, Credit>
     */
    public function getCredit(): Collection
    {
        return $this->Credit;
    }

    public function addCredit(Credit $credit): self
    {
        if (!$this->Credit->contains($credit)) {
            $this->Credit[] = $credit;
            $credit->setOperationCredit($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): self
    {
        if ($this->Credit->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getOperationCredit() === $this) {
                $credit->setOperationCredit(null);
            }
        }

        return $this;
    }
}
