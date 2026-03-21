<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ObjectifRepository;
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

#[ORM\Entity(repositoryClass: ObjectifRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['objectif:read']],
    denormalizationContext: ['groups' => ['objectif:write']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Put(),
        new Delete(),
    ]
)]
class Objectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['objectif:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['objectif:read', 'objectif:write'])]
    #[Assert\NotBlank(message: 'Le nom de l\'objectif ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        minMessage: 'Le nom de l\'objectif doit contenir au moins {{ limit }}
        caractères.',
    )]  
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['objectif:read', 'objectif:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['objectif:read', 'objectif:write'])]
    #[Assert\NotBlank(message: 'Le montant cible de l\'objectif ne peut pas être vide.')]
    #[Assert\Positive(message: 'Le montant cible de l\'objectif doit être un nombre positif.')]
    private ?string $montantCible = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['objectif:read'])]
    #[Assert\PositiveOrZero(message: 'Le montant actuel doit être positif ou égal à 0.')]
    private ?string $montantActuel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['objectif:read', 'objectif:write'])]
    private ?\DateTime $dateEcheance = null;

    #[ORM\Column(length: 20)]
    #[Groups(['objectif:read'])]
    #[Assert\NotBlank(message: 'Le statut de l\'objectif ne peut pas être vide.')]
    #[Assert\Choice(
        choices: ['en cours', 'atteint', 'abandonne'],
        message: 'Le statut de l\'objectif doit être l\'un des suivants : {{ choices }}.',
    )]
    private ?string $statut = 'en_cours';

    #[ORM\ManyToOne(inversedBy: 'objectifs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['objectif:read'])]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['objectif:read'])]
    private ?DateTime $createdAt = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMontantCible(): ?string
    {
        return $this->montantCible;
    }

    public function setMontantCible(string $montantCible): static
    {
        $this->montantCible = $montantCible;

        return $this;
    }

    public function getMontantActuel(): ?string
    {
        return $this->montantActuel;
    }

    public function setMontantActuel(string $montantActuel): static
    {
        $this->montantActuel = $montantActuel;

        return $this;
    }

    public function getDateEcheance(): ?\DateTime
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(?\DateTime $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

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

    public function getCreatedAt(): ?DateTime { return $this->createdAt; }
}
