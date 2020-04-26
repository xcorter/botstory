<?php

namespace App\Core\Question\Entity;

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
    private $isFinish = false;

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
     * Question constructor.
     * @param Game $game
     * @param bool $isStart
     */
    public function __construct(Game $game, bool $isStart)
    {
        $this->game = $game;
        $this->isStart = $isStart;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function isStart(): bool
    {
        return $this->isStart;
    }

    public function isFinish(): bool
    {
        return $this->isFinish;
    }

    public function getLocationX(): int
    {
        return $this->locationX;
    }

    public function getLocationY(): int
    {
        return $this->locationY;
    }

    public function setLocationX(int $locationX): Question
    {
        $this->locationX = $locationX;
        return $this;
    }

    public function setLocationY(int $locationY): Question
    {
        $this->locationY = $locationY;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'isStart' => $this->isStart(),
            'position' => [
                'x' => $this->getLocationX(),
                'y' => $this->getLocationY(),
            ],
            'answers' => []
        ];
    }

    public function belongsTo(Game $game): bool
    {
        return $this->getGame()->getId() === $game->getId();
    }
}
