<?php

namespace App\Core\Entity;

use App\Core\Node\Entity\Node;
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
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Node\Entity\Node")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     */
    private Node $question;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $text;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $action;
    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Node\Entity\Node")
     * @ORM\JoinColumn(name="next_node_id", referencedColumnName="id", nullable=true)
     */
    private ?Node $nextQuestion;

    public function __construct(Node $question, string $text, ?string $action)
    {
        $this->question = $question;
        $this->text = $text;
        $this->action = $action;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuestion(): Node
    {
        return $this->question;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }


    public function hasAction(): bool
    {
        return (bool) $this->action;
    }

    public function getNextNode(): ?Node
    {
        return $this->nextQuestion;
    }

    public function setText(string $text): Answer
    {
        $this->text = $text;
        return $this;
    }

    public function setNextQuestion(?Node $nextQuestion): Answer
    {
        $this->nextQuestion = $nextQuestion;
        return $this;
    }

    public function toArray(): array
    {
        $nextQuestionId =
            $this->getNextNode() ? $this->getNextNode()->getId() : null;
        return [
            'nextQuestionId' => $nextQuestionId,
            'id' => $this->getId(),
            'text' => $this->getText()
        ];
    }
}
