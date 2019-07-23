<?php

namespace App\Core\Update;

use App\Core\Entity\UpdateLog;
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
        return (bool) $this->entityManager->getRepository(UpdateLog::class)->findOneBy([
            'updateId' => $updateId,
            'provider' => $provider,
        ]);
    }

    public function save(UpdateLog $update): void
    {
        $this->entityManager->persist($update);
        $this->entityManager->flush();
    }
}
