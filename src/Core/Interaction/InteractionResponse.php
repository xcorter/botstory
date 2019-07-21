<?php

namespace App\Core\Interaction;

class InteractionResponse
{

    /**
     * @var string
     */
    private $chatId;

    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $keyboard;

    /**
     * InteractionResponse constructor.
     * @param string $chatId
     * @param string $text
     */
    public function __construct(
        string $chatId,
        string $text
    ) {
        $this->chatId = $chatId;
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getKeyboard(): array
    {
        return $this->keyboard;
    }

    /**
     * @param array $keyboard
     */
    public function setKeyboard(array $keyboard): void
    {
        $this->keyboard = $keyboard;
    }
}
