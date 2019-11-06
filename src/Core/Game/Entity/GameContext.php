<?php

namespace App\Core\Game\Entity;

use App\Core\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class GameContext
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     * @var Game
     */
    private $game;
    /**
     * @ORM\Column(type="string", length=1024)
     * @var string $author
     */
    private $context;

    /**
     * GameContext constructor.
     * @param User $user
     * @param Game $game
     * @param string $context
     */
    public function __construct(User $user, Game $game, string $context)
    {
        $this->user = $user;
        $this->game = $game;
        $this->context = $context;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
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
    public function getContext(): string
    {
        return $this->context;
    }

    public function setContext(string $context): void
    {
        $this->context = $context;
    }
}
