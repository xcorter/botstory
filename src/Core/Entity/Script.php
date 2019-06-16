<?php

namespace App\Core\Entity;

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
     * @ORM\ManyToOne(targetEntity="App/Core/Entity/Game" type="integer" inversedBy="id", unique="true")
     * @var integer $game
     * @JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    public function __construct(string $text, int $step, int $game)
    {
        $this->text = $text;
        $this->step = $step;
        $this->game = $game;
    }

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
    public function getGame(): int
    {
        return $this->game;
    }
}
