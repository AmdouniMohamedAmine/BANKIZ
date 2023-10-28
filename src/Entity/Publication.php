<?php

namespace App\Entity;
use App\Controller\SubmitType;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PublicationRepository::class)
 */
class Publication
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="publications")
     */
    private $nom_client;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min="5" , minMessage="minimum 5 caracteres")
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $Titre_publication;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min="10" , minMessage="minimum 10 caracteres")
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $Description_publication;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie_publication;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_publication;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="id")
     */
    private $commentaires;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Choisir une image est obligatoire")
     */
    private $image_publication;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="Publication")
     */
    private $likes;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?Utilisateur
    {
        return $this->nom_client;
    }

    public function setNomClient(?Utilisateur $nom_client): self
    {
        $this->nom_client = $nom_client;

        return $this;
    }

    public function getTitrePublication(): ?string
    {
        return $this->Titre_publication;
    }

    public function setTitrePublication(?string $Titre_publication): self
    {
        $this->Titre_publication = $Titre_publication;

        return $this;
    }

    public function getDescriptionPublication(): ?string
    {
        return $this->Description_publication;
    }

    public function setDescriptionPublication(?string $Description_publication): self
    {
        $this->Description_publication = $Description_publication;

        return $this;
    }

    public function getCategoriePublication(): ?string
    {
        return $this->categorie_publication;
    }

    public function setCategoriePublication(string $categorie_publication): self
    {
        $this->categorie_publication = $categorie_publication;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setId($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getId() === $this) {
                $commentaire->setId(null);
            }
        }

        return $this;
    }

    public function getImagePublication()
    {
        return $this->image_publication;
    }

    public function setImagePublication( $image_publication)
    {
        $this->image_publication = $image_publication;

        return $this;
    }
    public function __toString()
    {
        return $this->Titre_publication;
    }

    /**
     * @return Collection|PostLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PostLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPublication($this);
        }

        return $this;
    }

    public function removeLike(PostLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPublication() === $this) {
                $like->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * Permet de savoir si cet article est like par user
     * @param Utilisateur $utilisateur
     * @return boolean
     */
    public function isLikedByUser(Utilisateur $utilisateur) : bool
    {
        foreach ($this->likes as $like) {
            if ($like->getNomClient() === $utilisateur)
                return true;
        }
        return false;
    }
}