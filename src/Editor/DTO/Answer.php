<?php

namespace App\Editor\DTO;

use JMS\Serializer\Annotation\Type;

class Answer
{
    /**
     * @Type("int")
     */
    private ?int $id;

    /**
     * @Type("int")
     */
    private ?int $nextQuestionId = null;

    /**
     * @Type("string")
     */
    private string $text;

    /**
     * @Type("string")
     */
    private ?string $viewId = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
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

    /**
     * @return string|null
     */
    public function getViewId(): ?string
    {
        return $this->viewId;
    }
}
