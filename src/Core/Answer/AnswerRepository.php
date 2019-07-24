<?php

namespace App\Core\Answer;

use App\Core\Entity\Answer;
use App\Core\Entity\Script;
use Doctrine\ORM\EntityManagerInterface;

class AnswerRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AnswerRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Script $script
     * @return Answer[]
     */
    public function findByScript(Script $script): array
    {
        return $this->entityManager->getRepository(Answer::class)->findBy([
            'script' => $script,
        ]);
    }
}
