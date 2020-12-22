<?php

namespace App\Core\Interaction\Action;

use App\Core\Interaction\ActionOperation;
use App\Core\Interaction\FormulaLanguage\FormulaParser;

class ActionFactory
{
    private FormulaParser $parser;

    public function __construct(FormulaParser $parser)
    {
        $this->parser = $parser;
    }

    public function createAction(array $action): ActionInterface
    {
        switch ($action['operation']) {
            case ActionOperation::SUM:
                return new SumAction($action['target'], $action['value']);
            case ActionOperation::DIFF:
                return new DiffAction($action['target'], $action['value']);
            case ActionOperation::SET:
                return new SetAction($action['target'], $action['value']);
            case ActionOperation::FORMULA:
                $node = $this->parser->parse($action['value']);
                return new FormulaAction($action['target'], $node);
        }
        throw new \DomainException('Operation not found, id: ' . $action['operation']);
    }
}
