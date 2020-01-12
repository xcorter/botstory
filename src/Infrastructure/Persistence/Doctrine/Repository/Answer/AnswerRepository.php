<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Answer;

use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Entity\Answer;
use App\Core\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class AnswerRepository implements AnswerRepositoryInterface
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
     * @param Question $question
     * @return Answer[]
     */
    public function findByQuestion(Question $question): array
    {
        return $this->entityManager->getRepository(Answer::class)->findBy([
            'question' => $question,
        ]);
    }
}