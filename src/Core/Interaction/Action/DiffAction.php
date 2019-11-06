<?php

namespace App\Core\Interaction\Action;

use App\Core\Game\Entity\GameContext;
use App\Core\Interaction\ActionOperation;

class DiffAction implements ActionInterface
{
    /**
     * @var int
     */
    private $operation = ActionOperation::DIFF;
    /**
     * @var string
     */
    private $target;
    /**
     * @var int
     */
    private $value;

    /**
     * SumAction constructor.
     * @param string $target
     * @param int $value
     */
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
                $characteristics[$key]['value_int'] -= $this->value;
                $gameContext->setContext(json_encode($characteristics));
                return;
            }
        }
    }
}
