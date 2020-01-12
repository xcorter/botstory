<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Question;

use App\Core\Game\Entity\Game;
use App\Core\Entity\Question;
use App\Core\Question\QuestionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

/**
 * @method createQueryBuilder(string $string)
 */
class QuestionRepository implements QuestionRepositoryInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ScriptRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAllQuestionsByGameId(int $gameId): array
    {
        return $this->entityManager->getRepository(Question::class)->findBy(
            [
                'game' => $gameId
            ]
        );
    }

    public function getPaginatorQuery(int $gameId): Query
    {
        return $this->entityManager->createQueryBuilder()
            ->select('q.id', 'q.text')
            ->from(Question::class, 'q')
            ->andWhere('q.game = :gameId')
            ->setParameter('gameId', $gameId)
            ->getQuery();
    }

    public function save(Question $question): void
    {
        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }

    public function findQuestion(int $id): ?Question
    {
        return $this->entityManager->getRepository(Question::class)->find($id);
    }

    public function getStartQuestion(int $gameId): Question
    {
        return $this->entityManager->getRepository(Question::class)
            ->findOneBy([
                'game' => $gameId,
                'isStart' => true,
            ]);
    }


}
