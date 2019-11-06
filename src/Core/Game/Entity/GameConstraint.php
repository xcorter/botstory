<?php

namespace App\Core\Game\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class GameConstraint
{
    public const LESS = 1;
    public const LESS_OR_EQUALS = 2;
    public const MORE = 3;
    public const MORE_OR_EQUALS = 4;
    public const EQUALS = 5;

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
     * @ORM\ManyToOne(targetEntity="Characteristic")
     * @ORM\JoinColumn(name="characteristic_id", referencedColumnName="id")
     * @var Characteristic
     */
    private $characteristic;
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
     * GameConstraint constructor.
     * @param Game $game
     * @param Characteristic $characteristic
     * @param int $type
     * @param string|null $valueString
     * @param int|null $valueInt
     */
    public function __construct(Game $game, Characteristic $characteristic, int $type, ?string $valueString, ?int $valueInt)
    {
        $this->game = $game;
        $this->characteristic = $characteristic;
        $this->type = $type;
        $this->valueString = $valueString;
        $this->valueInt = $valueInt;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @return Characteristic
     */
    public function getCharacteristic(): Characteristic
    {
        return $this->characteristic;
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
