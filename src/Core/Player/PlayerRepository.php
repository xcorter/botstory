<?php

namespace App\Core\Player;

use App\Core\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class PlayerRepository
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
     * @return Player|null
     */
    public function findProviderUserId(string $providerUserId, string $providerName): ?Player
    {
        return $this->entityManager->getRepository(Player::class)->findOneBy([
            'providerName' => $providerName,
            'providerUserId' => $providerUserId,
        ]);
    }

    public function save(Player $user): void
    {

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
