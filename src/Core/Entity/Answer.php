<?php

namespace App\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Answer
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Script")
     * @ORM\JoinColumn(name="script_id", referencedColumnName="id")
     * @var Script
     */
    private $script;
    /**
     * @ORM\Column(type="string", length=255)
     * @var string $text
     */
    private $text;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null $action
     */
    private $action;
    /**
     * @ORM\ManyToOne(targetEntity="Script")
     * @ORM\JoinColumn(name="next_script_id", referencedColumnName="id", nullable=true)
     * @var Script|null
     */
    private $nextScript;

    /**
     * Answer constructor.
     * @param Script $script
     * @param string $text
     * @param string|null $action
     */
    public function __construct(Script $script, string $text, ?string $action)
    {
        $this->script = $script;
        $this->text = $text;
        $this->action = $action;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Script
     */
    public function getScript(): Script
    {
        return $this->script;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @return bool
     */
    public function hasAction(): bool
    {
        return (bool) $this->action;
    }

    /**
     * @return Script|null
     */
    public function getNextScript(): ?Script
    {
        return $this->nextScript;
    }
}
