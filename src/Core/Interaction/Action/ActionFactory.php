<?php

namespace App\Core\Interaction\Action;

class ActionFactory
{

    public function createAction(array $action): ActionInterface
    {
        switch ($action['operation']) {
            case 1:
                return new SumAction($action['target'], $action['value']);
            case 2:
                return new DiffAction($action['target'], $action['value']);
            case 3:
                return new EquatingAction($action['target'], $action['value']);
        }
        throw new \DomainException('Operation not found, id: ' . $action['operation']);
    }
}
