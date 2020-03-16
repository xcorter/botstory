<?php

namespace App\Editor\DTO;

use JMS\Serializer\Annotation\Type;

class Node
{

    /**
     * @var int|null $id
     * @Type("integer")
     */
    private $id;

    /**
     * @var string $text
     * @Type("string")
     */
    private $text;

    /**
     * @var NodePosition
     * @Type("App\Editor\DTO\NodePosition")
     */
    private $position;

    /**
     * @var Answer[]
     * @Type("array<App\Editor\DTO\Answer>")
     */
    private $answers;

    /**
     * @return int|null
     */
    public function getId(): ?int
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
     * @return NodePosition
     */
    public function getPosition(): NodePosition
    {
        return $this->position;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }
}
