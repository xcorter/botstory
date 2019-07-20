<?php

namespace App\Core\User;

class UserContext
{

    private $currentGame;

    /**
     * @param mixed $currentGame
     * @return UserContext
     */
    public function setCurrentGame($currentGame): UserContext
    {
        $this->currentGame = $currentGame;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentGame()
    {
        return $this->currentGame;
    }

}
