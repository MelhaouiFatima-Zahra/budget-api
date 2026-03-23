<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class TransactionProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){}
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
       if ($data instanceof Transaction){
            $compte = $data->getCompte();
            $solde = (float) $compte->getSolde();
            $montant = (float) $data->getMontant();

            if ($data->getType() === 'revenu') {
                $solde += $montant;
            } elseif ($data->getType() === 'depense') {
                $solde -= $montant;
            }
            $compte->setSolde((string) $solde);
       }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }
}
