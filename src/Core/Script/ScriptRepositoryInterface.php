<?php

namespace App\Core\Script;

use App\Core\Entity\Game;
use App\Core\Entity\Script;
use Doctrine\ORM\Query;

interface ScriptRepositoryInterface
{
    public const FIRST_STEP = 1;

    public function findAllScriptsByGameId(int $gameId): array;

    public function getPaginatorQuery(int $gameId): Query;

    public function save(Script $script): void;

    public function getScript(Game $game, int $step): Script;
}
