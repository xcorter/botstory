<?php

namespace App\Core\Interaction\Constraint;

use App\Core\Game\Entity\GameConstraint;

class ConstraintFactory
{
    public function create(GameConstraint $gameConstraint): ConstraintInterface
    {
        if ($gameConstraint->getType() === GameConstraint::LESS) {
            return new LessConstraint(
                $gameConstraint->getCharacteristic()->getId(),
                $gameConstraint->getValueInt(),
                false
            );
        } elseif ($gameConstraint->getType() === GameConstraint::LESS_OR_EQUALS) {
            return new LessConstraint(
                $gameConstraint->getCharacteristic()->getId(),
                $gameConstraint->getValueInt(),
                true
            );
        } elseif ($gameConstraint->getType() === GameConstraint::MORE) {
            return new MoreConstraint(
                $gameConstraint->getCharacteristic()->getId(),
                $gameConstraint->getValueInt(),
                false
            );
        } elseif ($gameConstraint->getType() === GameConstraint::MORE_OR_EQUALS) {
            return new MoreConstraint(
                $gameConstraint->getCharacteristic()->getId(),
                $gameConstraint->getValueInt(),
                true
            );
        }
        throw new \DomainException('Type not found');
    }
}
