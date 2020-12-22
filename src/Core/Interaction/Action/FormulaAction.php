<?php

namespace App\Core\Interaction\Action;

use App\Core\Game\Entity\GameContext;
use App\Core\Interaction\ActionOperation;
use MathParser\Parsing\Nodes\Node;

class FormulaAction implements ActionInterface
{
    private int $operation = ActionOperation::FORMULA;
    private string $target;
    private Node $node;

    public function __construct(string $target, Node $node)
    {
        $this->target = $target;
        $this->node = $node;
    }

    public function execute(GameContext $gameContext): void
    {
        $characteristics = $gameContext->getContext();
        $characteristics = json_decode($characteristics, true);
        $values = array_map(function (array $characteristic) {
            if ($characteristic['value_int'] ?? null) {
                return $characteristic;
            }
            return null;
        }, $characteristics);
        $values = array_filter($values);
        $values = array_reduce($values, function(array $carry , array $item ) {
            $carry[$item['name']] = $item['value_int'];
            return $carry;
        }, []);
        $value = $this->node->evaluate($values);
        foreach ($characteristics as $key => $characteristic) {
            if ($characteristic['name'] === $this->target) {
                $characteristics[$key]['value_int'] = $value;
                $gameContext->setContext(json_encode($characteristics));
                return;
            }
        }
    }
}