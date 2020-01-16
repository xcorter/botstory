<?php

namespace App\Core\Admin\Game;

use Psr\Log\LoggerInterface;

class UpdateEntityHelper
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UpdateEntityHelper constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setToEntity($entity, array $fields)
    {
        // TODO проверять типы значений  BOT-51
        // @link https://trello.com/c/aHR9cSpb
        foreach ($fields as $field => $value) {
            $method = $this->getSetterByField($field);
            if (method_exists($entity, $method)) {
                $entity->$method($value);
            } else {
                $this->logger->error('Method not found', [
                    'method' => $method,
                    'questionId' => $entity
                ]);
            }
        }
    }

    private function getSetterByField(string $field): string
    {
        return 'set' . ucfirst($field);
    }
}