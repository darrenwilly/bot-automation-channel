<?php
declare(strict_types=1);
namespace Veiw\BotLogic\Core\EventSubscriber\BotKeyword;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Veiw\BotLogic\Core\Event\BotEvent;
use Veiw\BotLogic\Core\Event\BotKeywordEvent;


class StopKeywordTargetExecutionSubscriber implements EventSubscriberInterface
{
    protected $container ;

    static public function getSubscribedEvents()
    {
        return [
            BotEvent::BOT_KEYWORD =>  [
                ['executeTarget' , -100] ,
            ]
        ];
    }

    public function executeTarget(BotKeywordEvent $keywordEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        if(! $keywordEvent->hasResponse())    {
            ## create a default result that will be sent if the keyword failed to produce result at this point
        }
        ## At this point we don't want anything to run again
        $keywordEvent->stopPropagation();
    }

}