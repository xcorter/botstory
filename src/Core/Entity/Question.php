<?php

namespace App\Core\Entity;

use App\Core\Game\Entity\Game;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Question
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
     * @ORM\Column(type="integer", options={"default":"0"})
     * @var int $locationX
     */
    private $locationX;

    /**
     * @ORM\Column(type="integer", options={"default":"0"})
     * @var int $locationX
     */
    private $locationY;

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

    /**
     * @return int
     */
    public function getLocationX(): int
    {
        return $this->locationX;
    }

    /**
     * @return int
     */
    public function getLocationY(): int
    {
        return $this->locationY;
    }

    /**
     * @param int $locationX
     * @return Question
     */
    public function setLocationX(int $locationX): Question
    {
        $this->locationX = $locationX;
        return $this;
    }

    /**
     * @param int $locationY
     * @return Question
     */
    public function setLocationY(int $locationY): Question
    {
        $this->locationY = $locationY;
        return $this;
    }
}
