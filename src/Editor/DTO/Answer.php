<?php

namespace App\Editor\DTO;

use JMS\Serializer\Annotation\Type;

class Answer
{
    /**
     * @var int
     * @Type("int")
     */
    private $id;

    /**
     * @var int
     * @Type("int")
     */
    private $nextQuestionId;

    /**
     * @var string
     * @Type("string")
     */
    private $text;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNextQuestionId(): int
    {
        return $this->nextQuestionId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}