<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CategorieProcessor implements ProcessorInterface
{
    public function __construct(
       private EntityManagerInterface $entityManager, 
       private Security $security
    ){}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
       if($data instanceof Categorie){
            $user = $this->security->getUser();
            $data->setUser($user);
       }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
