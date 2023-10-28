<?php

namespace App\Entity;
use App\Entity\Utilisateur;
use App\Entity\Publication;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Publication::class, inversedBy="commentaires")
     */
    private $id_publication;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="commentaires")
     */
    private $nom_client;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min="10" , minMessage="minimum 10 caracteres")
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $description_commentaire;

    /**
     * @ORM\Column(type="date")
     */
    private $date_commentaire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaire::class, inversedBy="replies")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="parent")
     */
    private $replies;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCommentaire(): ?int
    {
        return $this->id_commentaire;
    }

    public function setIdCommentaire(int $id_commentaire): self
    {
        $this->id_commentaire = $id_commentaire;

        return $this;
    }

    public function getIdPublication(): ?Publication
    {
        return $this->id_publication;
    }

    public function setIdPublication(?Publication $id_publication): self
    {
        $this->id_publication = $id_publication;

        return $this;
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

    public function getDescriptionCommentaire(): ?string
    {
        return $this->description_commentaire;
    }

    public function setDescriptionCommentaire(string $description_commentaire): self
    {
        $this->description_commentaire = $description_commentaire;

        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->date_commentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $date_commentaire): self
    {
        $this->date_commentaire = $date_commentaire;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(self $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setParent($this);
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getParent() === $this) {
                $reply->setParent(null);
            }
        }

        return $this;
    }
}