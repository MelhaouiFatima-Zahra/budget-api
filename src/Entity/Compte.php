<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CompteRepository;
use App\State\CompteProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['compte:read']],
    denormalizationContext: ['groups' => ['compte:write']],
    operations: [
        new Get(),
        new Post(processor: CompteProcessor::class),
        new Put(processor: CompteProcessor::class),
        new Delete(),
    ]
)]
class Compte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['compte:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['compte:read', 'compte:write'])]
    #[Assert\NotBlank(message: 'Le nom du compte ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        minMessage: 'Le nom du compte doit contenir au moins {{ limit }} caractères.',
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Groups(['compte:read', 'compte:write'])]
    #[Assert\NotBlank(message: 'Le type de compte ne peut pas être vide.')]
    #[Assert\Choice(
        choices: ['courant', 'epargne', 'investissement'],
        message: 'Le type de compte doit être l\'un des suivants : {{ choices }}.',
    )]
    private ?string $type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['compte:read', 'compte:write'])]
    #[Assert\NotBlank(message: 'Le solde du compte ne peut pas être vide.')]
    private ?string $solde = null;

    #[ORM\Column(length: 3)]
    #[Groups(['compte:read', 'compte:write'])]
    #[Assert\NotBlank(message: 'La devise du compte ne peut pas être vide.')]
    #[Assert\Length(
        min: 3,
        max: 3,
        exactMessage: 'La devise du compte doit être exactement {{ limit }} caractères.',
    )]
    private ?string $devise = null;

    #[ORM\Column(length: 7, nullable: true)]
    #[Groups(['compte:read', 'compte:write'])]

    private ?string $couleur = null;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['compte:read'])]
    private ?DateTime $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'comptes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['compte:read'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'compte')]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(string $solde): static
    {
        $this->solde = $solde;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): static
    {
        $this->devise = $devise;

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
            $transaction->setCompte($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompte() === $this) {
                $transaction->setCompte(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?DateTime { return $this->createdAt; }

}
