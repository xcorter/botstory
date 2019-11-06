<?php

namespace App\Core\Game;

use App\Core\Game\Entity\Characteristic;

class CharacteristicService
{

    /**
     * @param array $characteristics
     * @return array
     */
    public function serializeArray(array $characteristics): array
    {
        $result = [];
        foreach ($characteristics as $characteristic) {
            $result[] = $this->serialize($characteristic);
        }
        return $result;
    }

    public function serialize(Characteristic $characteristic): array
    {
        return [
            'id' => $characteristic->getId(),
            'game_id' => $characteristic->getGame()->getId(),
            'name' => $characteristic->getName(),
            'type' => $characteristic->getType(),
            'value_int' => $characteristic->getValueInt(),
            'value_string' => $characteristic->getValueString(),
        ];
    }
}
