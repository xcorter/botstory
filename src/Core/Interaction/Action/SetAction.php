<?php

namespace App\Core\Interaction\Action;

use App\Core\Game\Entity\GameContext;
use App\Core\Interaction\ActionOperation;

class SetAction implements ActionInterface
{
    private int $operation = ActionOperation::SET;
    private string $target;
    private int $value;

    public function __construct(string $target, int $value)
    {
        $this->target = $target;
        $this->value = $value;
    }

    public function execute(GameContext $gameContext): void
    {
        $characteristics = $gameContext->getContext();
        $characteristics = json_decode($characteristics, true);
        foreach ($characteristics as $key => $characteristic) {
            if ($characteristic['name'] === $this->target) {
                $characteristics[$key]['value_int'] = $this->value;
                $gameContext->setContext(json_encode($characteristics));
                return;
            }
        }
    }
}
