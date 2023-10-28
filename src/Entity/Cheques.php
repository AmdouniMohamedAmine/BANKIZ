<?php

namespace App\Entity;

use App\Repository\ChequesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ChequesRepository::class)
 */
class Cheques
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_cheques;

    /**
     * @ORM\Column(type="float", length=255)
     * @Assert\Length( min = 3 , minMessage = "Veuillez saisir un montant >100")
     * @Assert\NotBlank(message="Veuillez saisir le montant *")
     * @Assert\Type(type="numeric", message="Le nombre ne doit pas contenir des caractères .")


     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="cheques")

     */
    private $proprietaire;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThanOrEqual("today", message="La date est incorrecte .")
     */

    private $date_cheque;

    /**
     * @ORM\Column(type="string", length=100)


     * @Assert\Length( min = 4 , minMessage = "Veuillez saisir une addresse valide")
     * @Assert\NotBlank(message="Veuillez saisir le lieu*")


     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)

     * @Assert\NotBlank(message=" Signature ou code obligatoire *")
     */
    private $signature;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min = 3 , minMessage = "Veuillez saisir un nom valide")
     * @Assert\NotBlank(message="Veuillez saisir le destinataire *")
     * @Assert\Type(type="alpha", message="Le nom ne doit pas contenir des chiffres .")
     */
    private $destinataire;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $no;

    /**s
     * @ORM\ManyToOne(targetEntity=Chequier::class, inversedBy="cheque")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Carnets_cheques;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCheques(): ?int
    {
        return $this->id_cheques;
    }

    public function setIdCheques(int $id_cheques): self
    {
        $this->id_cheques = $id_cheques;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getProprietaire(): ?Compte
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Compte $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getDateCheque(): ?\DateTimeInterface
    {
        return $this->date_cheque;
    }

    public function setDateCheque(\DateTimeInterface $date_cheque): self
    {
        $this->date_cheque = $date_cheque;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }


    public function setDestinataire(string $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    //  public function __toString()
    // {
    //  return (string) $this->getCarnet_cheques();
    // }

    public function getNo(): ?string
    {
        return $this->no;
    }

    public function setNo(string $no): self
    {
        $this->no = $no;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getCarnetsCheques();

    }

    public function getCarnetsCheques(): ?Chequier
    {
        return $this->Carnets_cheques;
    }

    public function setCarnetsCheques(?Chequier $Carnets_cheques): self
    {
        $this->Carnets_cheques = $Carnets_cheques;

        return $this;
    }
}