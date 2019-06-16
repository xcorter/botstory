<?php

namespace App\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Game
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="App/Core/Entity/Script", unique="true")
     * @var integer $id
     */
    private $id;
    /**
     *
     * @var string $name
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $author
     */
    private $author;

    /**
     * Game constructor.
     * @param string $name
     * @param string $author
     */
    public function __construct(string $name, string $author)
    {
        $this->name = $name;
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
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
}
