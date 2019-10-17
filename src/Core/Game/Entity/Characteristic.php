<?php

namespace App\Core\Game\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Characteristic
{
    public const TYPE_STRING = 1;
    public const TYPE_INT = 2;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     * @var Game
     */
    private $game;
    /**
     * @ORM\Column(type="string", length=255)
     * @var string $name
     */
    private $name;
    /**
     * @ORM\Column(type="integer")
     * @var int $type
     */
    private $type;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null $valueString
     */
    private $valueString;
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null $valueInt
     */
    private $valueInt;

    /**
     * Characteristic constructor.
     * @param Game $game
     * @param string $name
     * @param int $type
     * @param string|null $valueString
     * @param int|null $valueInt
     */
    public function __construct(Game $game, string $name, int $type, ?string $valueString, ?int $valueInt)
    {
        $this->game = $game;
        $this->name = $name;
        $this->type = $type;
        $this->valueString = $valueString;
        $this->valueInt = $valueInt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getValueString(): ?string
    {
        return $this->valueString;
    }

    /**
     * @return int|null
     */
    public function getValueInt(): ?int
    {
        return $this->valueInt;
    }
}
