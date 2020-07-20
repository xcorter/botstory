<?php

namespace App\Editor\DTO;

use JMS\Serializer\Annotation\Type;

class Node
{

    /**
     * @var int|null $id
     * @Type("integer")
     */
    private ?int $id;

    /**
     * @var string $text
     * @Type("string")
     */
    private string $text;

    /**
     * @var NodePosition
     * @Type("App\Editor\DTO\NodePosition")
     */
    private NodePosition $position;

    /**
     * @var Answer[]
     * @Type("array<App\Editor\DTO\Answer>")
     */
    private array $answers;

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
