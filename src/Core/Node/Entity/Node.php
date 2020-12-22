<?php

namespace App\Core\Node\Entity;

use App\Core\Entity\Answer;
use App\Core\Game\Entity\Game;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Node
{
    public const TYPE_TEXT = 1;
    public const TYPE_ACTION = 2;
    public const TYPE_LOGIC = 3;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private string $text;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private bool $isStart;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private bool $isFinish = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Game\Entity\Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private Game $game;

    /**
     * @ORM\Column(type="integer", options={"default":"0"})
     */
    private int $locationX;

    /**
     * @ORM\Column(type="integer", options={"default":"0"})
     */
    private int $locationY;

    /**
     * @ORM\Column(type="integer", options={"default":"1"})
     */
    private int $type = 1;

    /**
     * @ORM\Column(type="string")
     */
    private string $actions;

    public function __construct(Game $game, bool $isStart)
    {
        $this->game = $game;
        $this->isStart = $isStart;
    }


    public function getId(): ?int
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

    public function setLocationX(int $locationX): Node
    {
        $this->locationX = $locationX;
        return $this;
    }

    public function setLocationY(int $locationY): Node
    {
        $this->locationY = $locationY;
        return $this;
    }

    public function toArray(array $answers): array
    {
        $answersArr = [];
        foreach ($answers as $answer) {
            $answersArr[] = $answer->toArray();
        }
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'isStart' => $this->isStart(),
            'position' => [
                'x' => $this->getLocationX(),
                'y' => $this->getLocationY(),
            ],
            'answers' => $answersArr,
        ];
    }

    public function belongsTo(Game $game): bool
    {
        return $this->getGame()->getId() === $game->getId();
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    public function getActions(): ?array
    {
        if (!$this->actions) {
            return null;
        }
        return json_decode($this->actions, true);
    }
}
