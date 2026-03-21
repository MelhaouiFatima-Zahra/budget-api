<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BudgetRepository;
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

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['budget:read']],
    denormalizationContext: ['groups' => ['budget:write']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Put(),
        new Delete(),
    ]
)]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['budget:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['budget:read', 'budget:write'])]
    #[Assert\NotBlank(message: 'Le montant du budget ne peut pas être vide.')]
    #[Assert\Positive(message: 'Le montant du budget doit être un nombre positif.')]
    private ?string $montant = null;

    #[ORM\Column]
    #[Groups(['budget:read', 'budget:write'])]
    #[Assert\NotBlank(message: 'Le mois du budget ne peut pas être vide.')]
    #[Assert\Range(
        min: 1,
        max: 12,
        notInRangeMessage: 'Le mois du budget doit être compris entre {{ min }} et {{ max }}.',
    )]
    private ?int $mois = null;

    #[ORM\Column]
    #[Groups(['budget:read', 'budget:write'])]
    #[Assert\NotBlank(message: 'L\'année du budget ne peut pas être vide.')]
    #[Assert\Range(
        min: 2020,
        max: 2030,
        notInRangeMessage: 'L\'année du budget doit être comprise entre {{ min }} et {{ max }}.',
    )]
    private ?int $annee = null;

    #[ORM\ManyToOne(inversedBy: 'budgets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['budget:read'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'budgets')]
    #[Groups(['budget:read', 'budget:write'])]
    #[Assert\NotBlank(message: 'La catégorie du budget ne peut pas être vide.')]
    private ?Categorie $categorie = null;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(['budget:read'])]
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

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(int $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

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
