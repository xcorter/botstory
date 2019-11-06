<?php

namespace App\Core\Interaction;

use App\Core\Game\Entity\Game;
use App\Core\Game\GameConstraintRepositoryInterface;
use App\Core\Interaction\Constraint\ConstraintComposite;
use App\Core\Interaction\Constraint\ConstraintFactory;
use App\Core\Interaction\Constraint\ConstraintInterface;

class ConstraintsFactory
{
    /**
     * @var GameConstraintRepositoryInterface
     */
    private $gameConstraintRepository;
    /**
     * @var ConstraintFactory
     */
    private $constraintFactory;

    /**
     * ConstraintsFactory constructor.
     * @param GameConstraintRepositoryInterface $gameConstraintRepository
     * @param ConstraintFactory $constraintFactory
     */
    public function __construct(GameConstraintRepositoryInterface $gameConstraintRepository, ConstraintFactory $constraintFactory)
    {
        $this->gameConstraintRepository = $gameConstraintRepository;
        $this->constraintFactory = $constraintFactory;
    }

    public function createConstraint(Game $game): ConstraintInterface
    {
        $gameConstraints = $this->gameConstraintRepository->findConstraints($game);
        $composite =  new ConstraintComposite();
        foreach ($gameConstraints as $gameConstraint) {
            $constraint = $this->constraintFactory->create($gameConstraint);
            $composite->addConstraint($constraint);
        }
        return $composite;
    }
}
