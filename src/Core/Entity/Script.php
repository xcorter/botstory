<?php

namespace App\Core\Entity;

use App\Core\Game\Entity\Game;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Script
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string $text
     */
    private $text;

    /**
     * @ORM\Column(type="integer")
     * @var integer $step
     */
    private $step;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     * @var bool
     */
    private $isStart;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     * @var bool
     */
    private $isFinish;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Game\Entity\Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     * @var Game
     */
    private $game;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * @return int
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param int $step
     */
    public function setStep(int $step): void
    {
        $this->step = $step;
    }

    /**
     * @param Game $game
     */
    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    /**
     * @return bool
     */
    public function isStart(): bool
    {
        return $this->isStart;
    }

    /**
     * @return bool
     */
    public function isFinish(): bool
    {
        return $this->isFinish;
    }
}
