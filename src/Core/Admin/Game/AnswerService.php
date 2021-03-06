<?php

namespace App\Core\Admin\Game;

use App\Core\Answer\AnswerRepositoryInterface;
use App\Core\Answer\Specification\IdSpecification;

class AnswerService
{
    /**
     * @var AnswerRepositoryInterface
     */
    private $answerRepository;
    /**
     * @var UpdateEntityHelper
     */
    private $updateEntityHelper;

    /**
     * AnswerService constructor.
     * @param AnswerRepositoryInterface $answerRepository
     * @param UpdateEntityHelper $updateEntityHelper
     */
    public function __construct(AnswerRepositoryInterface $answerRepository, UpdateEntityHelper $updateEntityHelper)
    {
        $this->answerRepository = $answerRepository;
        $this->updateEntityHelper = $updateEntityHelper;
    }

    public function updateAnswer(int $answerId, array $fields): void
    {
        $answer = $this->answerRepository->satisfyOneBy(new IdSpecification($answerId));
        if (!$answer) {
            throw new \OutOfRangeException('Answer not found');
        }
        $this->updateEntityHelper->setToEntity($answer, $fields);
        $this->answerRepository->save($answer);
    }

}