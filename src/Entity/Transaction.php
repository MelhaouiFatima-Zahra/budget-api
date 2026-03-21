<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['transaction:read']],
    denormalizationContext: ['groups' => ['transaction:write']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Put(),
        new Delete(),
    ]
)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['transaction:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['transaction:read', 'transaction:write'])]
    #[Assert\NotBlank(message: 'Le montant de la transaction ne peut pas être vide.')]
    #[Assert\Positive(message: 'Le montant de la transaction doit être un nombre positif.')]
    private ?string $montant = null;

    #[ORM\Column(length: 255)]
    #[Groups(['transaction:read', 'transaction:write'])]
    #[Assert\NotBlank(message: 'La description de la transaction ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        minMessage: 'La description de la transaction doit contenir au moins {{ limit }} caractères.',
    )]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    #[Groups(['transaction:read', 'transaction:write'])]
    #[Assert\NotBlank(message: 'Le type de transaction ne peut pas être vide.')]
    #[Assert\Choice(
        choices: ['revenu', 'depense'],
        message: 'Le type de transaction doit être l\'un des suivants : {{ choices }}.',
    )]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['transaction:read', 'transaction:write'])]
    #[Assert\NotBlank(message: 'La date de la transaction ne peut pas être vide.')]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private ?string $note = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)] 
    #[Groups(['transaction:read', 'transaction:write'])]
    #[Assert\NotBlank(message: 'Le compte associé à la transaction ne peut pas être vide.')]
    private ?Compte $compte = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[Groups(['transaction:read', 'transaction:write'])]
    private ?Categorie $categorie = null;

        
    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['transaction:read'])]
    private ?DateTime $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): static
    {
        $this->compte = $compte;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getCreatedAt(): ?DateTime { return $this->createdAt; }
}
