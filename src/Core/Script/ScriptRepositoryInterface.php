<?php

namespace App\Core\Script;

use App\Core\Game\Entity\Game;
use App\Core\Entity\Script;
use Doctrine\ORM\Query;

interface ScriptRepositoryInterface
{
    public const FIRST_STEP = 1;

    public function findAllScriptsByGameId(int $gameId): array;

    public function getPaginatorQuery(int $gameId): Query;

    public function save(Script $script): void;

    public function findNextScript(Game $game, Script $currentScript): ?Script;

    public function getScriptByStep(Game $game, int $step): Script;

    public function findScript(int $id): ?Script;
}
