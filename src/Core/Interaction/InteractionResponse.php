<?php

namespace App\Core\Interaction;

class InteractionResponse
{

    /**
     * @var string
     */
    private $text;

    /**
     * InteractionResponse constructor.
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}
