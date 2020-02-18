<?php

namespace App\Editor\DTO;

use JMS\Serializer\Annotation\Type;

class NodePosition
{

    /**
     * @var int $x
     * @Type("int")
     */
    private $x;

    /**
     * @var int y
     * @Type("int")
     */
    private $y;

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }
}