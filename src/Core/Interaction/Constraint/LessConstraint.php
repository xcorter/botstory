<?php

namespace App\Core\Interaction\Constraint;

use App\Core\Game\Entity\GameContext;

class LessConstraint extends AbstractConstraint
{
    /**
     * @var bool
     */
    private $orEquals;

    /**
     * AbstractConstraint constructor.
     * @param int $characteristicId
     * @param $value
     * @param bool $orEquals
     */
    public function __construct(int $characteristicId, $value, bool $orEquals)
    {
        parent::__construct($characteristicId, $value);
        $this->characteristicId = $characteristicId;
        $this->value = $value;
        $this->orEquals = $orEquals;
    }

    public function isSatisfiedBy(GameContext $gameContext): bool
    {
        $context = $gameContext->getContext();
        $context = json_decode($context, true);
        foreach ($context as $userValue) {
            if ($userValue['id'] === $this->characteristicId) {
                if ($this->orEquals) {
                    return $userValue['value_int'] >= $this->value;
                }
                return $userValue['value_int'] > $this->value;
            }
        }
        return true;
    }

}
