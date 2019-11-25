<?php

namespace App\Core\Answer;

use App\Core\Entity\Answer;
use App\Core\Entity\Question;
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
