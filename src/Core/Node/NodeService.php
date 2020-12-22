<?php

namespace App\Core\Node;

use App\Core\Game\Entity\Game;
use App\Core\Node\Entity\Node;

class NodeService
{
    private NodeRepositoryInterface $questionRepository;

    /**
     * QuestionService constructor.
     * @param NodeRepositoryInterface $questionRepository
     */
    public function __construct(NodeRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function createStartNode(Game $game): void
    {
        $question = new Node($game, true);
        $question
            ->setText('It starts here...')
            ->setLocationX(100)
            ->setLocationY(100)
        ;
        $this->questionRepository->save($question);
    }
}