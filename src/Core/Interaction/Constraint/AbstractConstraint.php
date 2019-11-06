<?php

namespace App\Core\Interaction\Constraint;

abstract class AbstractConstraint implements ConstraintInterface
{
    /**
     * @var int
     */
    protected $characteristicId;
    /**
     * @var
     */
    protected $value;

    /**
     * AbstractConstraint constructor.
     * @param int $characteristicId
     * @param $value
     */
    public function __construct(int $characteristicId, $value)
    {
        $this->characteristicId = $characteristicId;
        $this->value = $value;
    }
}
