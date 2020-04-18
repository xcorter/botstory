<?php


namespace App\Core\Question;


use App\Core\Game\Event\NewGame;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewGameSubscriber implements EventSubscriberInterface
{
    /**
     * @var QuestionService
     */
    private $questionService;

    /**
     * NewGameSubscriber constructor.
     * @param QuestionService $questionService
     */
    public function __construct(QuestionService $questionService)
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