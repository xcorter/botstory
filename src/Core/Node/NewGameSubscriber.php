<?php


namespace App\Core\Node;


use App\Core\Game\Event\NewGame;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewGameSubscriber implements EventSubscriberInterface
{
    /**
     * @var NodeService
     */
    private $questionService;

    /**
     * NewGameSubscriber constructor.
     * @param NodeService $questionService
     */
    public function __construct(NodeService $questionService)
    {
        $this->questionService = $questionService;
    }

    public static function getSubscribedEvents()
    {
        return [
            NewGame::NAME => 'onNewGame'
        ];
    }

    public function onNewGame(NewGame $event)
    {
        $this->questionService->createStartNode($event->getGame());
    }
}