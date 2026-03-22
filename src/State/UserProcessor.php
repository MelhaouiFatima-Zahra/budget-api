<?php

namespace App\State;

use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof User) {
            if ($data->getPlainPassword() !== null) {
                $hashedPassword = $this->hasher->hashPassword(
                    $data,
                    $data->getPlainPassword()
                );
                $data->setPassword($hashedPassword);
            }

            if (empty($data->getRoles())) {
                $data->setRoles(['ROLE_USER']);
            }
        }

        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }
}