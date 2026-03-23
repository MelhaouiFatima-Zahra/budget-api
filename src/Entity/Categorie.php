<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\State\CategorieProcessor;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['categorie:read']],
    denormalizationContext: ['groups' => ['categorie:write']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(processor: CategorieProcessor::class),
        new Put(processor: CategorieProcessor::class),
        new Delete(),
    ]
)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['categorie:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['categorie:read', 'categorie:write'])]
    #[Assert\NotBlank(message: 'Le nom de la catégorie ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        minMessage: 'Le nom de la catégorie doit contenir au moins {{ limit }} caractères',
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 7, nullable: true)]
    #[Groups(['categorie:read', 'categorie:write'])]
    #[Assert\Length(
        max: 7,
        maxMessage: 'La couleur de la catégorie ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $couleur = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['categorie:read', 'categorie:write'])]
    #[Assert\Length(
        max: 50,
        maxMessage: 'L\'icône de la catégorie ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $icone = null;

    #[ORM\Column(length: 20)]  
    #[Groups(['categorie:read', 'categorie:write'])]
    #[Assert\NotBlank(message: 'Le type de catégorie ne peut pas être vide.')]
    #[Assert\Choice(
        choices: ['revenu', 'depense', 'les deux'],
        message: 'Le type de catégorie doit être l\'un des suivants : {{ choices }}.',
    )]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[Groups(['categorie:read'])]
    private ?User $user = null;
    
    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['categorie:read'])]
    private ?DateTime $createdAt = null;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'categorie')]
    private Collection $transactions;

    /**
     * @var Collection<int, Budget>
     */
    #[ORM\OneToMany(targetEntity: Budget::class, mappedBy: 'categorie')]
    private Collection $budgets;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->budgets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone): static
    {
        $this->icone = $icone;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setCategorie($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCategorie() === $this) {
                $transaction->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Budget>
     */
    public function getBudgets(): Collection
    {
        return $this->budgets;
    }

    public function addBudget(Budget $budget): static
    {
        if (!$this->budgets->contains($budget)) {
            $this->budgets->add($budget);
            $budget->setCategorie($this);
        }

        return $this;
    }

    public function removeBudget(Budget $budget): static
    {
        if ($this->budgets->removeElement($budget)) {
            // set the owning side to null (unless already changed)
            if ($budget->getCategorie() === $this) {
                $budget->setCategorie(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?DateTime { return $this->createdAt; }
}
