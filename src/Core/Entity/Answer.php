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
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @var Question
     */
    private $question;
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
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="next_question_id", referencedColumnName="id", nullable=true)
     * @var Question|null
     */
    private $nextQuestion;

    /**
     * Answer constructor.
     * @param Question $question
     * @param string $text
     * @param string|null $action
     */
    public function __construct(Question $question, string $text, ?string $action)
    {
        $this->question = $question;
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
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
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
     * @return Question|null
     */
    public function getNextQuestion(): ?Question
    {
        return $this->nextQuestion;
    }
}
