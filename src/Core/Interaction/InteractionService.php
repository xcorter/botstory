<?php

namespace App\Core\Interaction;

class InteractionService
{

    public function getInfo(): InteractionResponse
    {
        return new InteractionResponse('hello world! choose your destiny');
    }
}
