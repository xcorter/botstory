<?php

namespace App\Editor\DTO;

use JMS\Serializer\Annotation\Type;

class Answer
{
    /**
     * @var null|int
     * @Type("int")
     */
    private $id;

    /**
     * @var null|int
     * @Type("int")
     */
    private $nextQuestionId;

    /**
     * @var string
     * @Type("string")
     */
    private $text;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getNextQuestionId(): ?int
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
