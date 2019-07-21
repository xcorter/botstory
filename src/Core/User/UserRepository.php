<?php

namespace App\Core\User;

use App\Core\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $providerUserId
     * @param string $providerName
     * @return User|null
     */
    public function findProviderUserId(string $providerUserId, string $providerName): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy([
            'providerName' => $providerName,
            'providerUserId' => $providerUserId,
        ]);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
