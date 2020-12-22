<?php

namespace App\Core\Interaction\Action;

abstract class ActionAbstract implements ActionInterface
{

    protected function findCharacteristicByName(array $characteristics, string $name)
    {
        foreach ($characteristics as $key => $characteristic) {
            if ($characteristic['name'] === $name) {
                return $characteristic;
            }
        }
        throw new \DomainException("Characteristic $name not found");
    }
}