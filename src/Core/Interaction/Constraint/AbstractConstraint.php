<?php

namespace App\Core\Interaction\Constraint;

abstract class AbstractConstraint implements ConstraintInterface
{
    protected int $characteristicId;
    /**
     * @var mixed
     */
    protected $value;

    /**
     * AbstractConstraint constructor.
     * @param int $characteristicId
     * @param mixed $value
     */
    public function __construct(int $characteristicId, $value)
    {
        $this->characteristicId = $characteristicId;
        $this->value = $value;
    }
}
