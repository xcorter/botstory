<?php

namespace App\Core\Update;

use App\Core\Entity\Update;
use Doctrine\ORM\EntityManagerInterface;

class UpdateRepository
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UpdateRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $updateId
     * @param string $provider
     * @return bool
     */
    public function updateExists(int $updateId, string $provider): bool
    {
        return (bool) $this->entityManager->getRepository(Update::class)->findOneBy([
            'updateId' => $updateId,
            'provider' => $provider,
        ]);
    }

    public function save(Update $update): void
    {
        $this->entityManager->persist($update);
        $this->entityManager->flush();
    }
}
