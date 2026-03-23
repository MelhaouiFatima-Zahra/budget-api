<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CompteProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security
    )
    {}
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
       if($data instanceof Compte){
            $user = $this->security->getUser();
            $data->setUser($user);

            if($data->getSolde() === null){
                $data->setSolde('0.00');
            }

            if ($data->getDevise() === null) {
                $data->setDevise('EUR');
            }
       }

     
        
        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }

}
