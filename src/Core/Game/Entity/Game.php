<?php

namespace App\Core\Game\Entity;

use App\Core\User\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity()
 */
class Game
{
    public const ACTION_EDIT = 'edit';

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @var string $name
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default": "CURRENT_TIMESTAMP"})
     * @var DateTimeImmutable $createdAt
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\User\Entity\User")
     * @JoinColumn(name="author_id", referencedColumnName="id")
     * @var User
     */
    private $author;

    /**
     * Game constructor.
     * @param string $name
     * @param User $author
     */
    public function __construct(string $name, User $author)
    {
        $this->name = $name;
        $this->createdAt = new DateTimeImmutable();
        $this->author = $author;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param string $name
     * @return Game
     */
    public function setName(string $name): Game
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    public function belongsToUser(User $user): bool
    {
        return $user->getId() && $this->author->getId() === $user->getId();
    }
}
