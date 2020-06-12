<?php
declare(strict_types=1);
namespace Veiw\BotLogic\Core\EventSubscriber\BotKeyword;

use DV\MicroService\TraitContainer;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Veiw\BotLogic\Core\Event\BotEvent;
use Veiw\BotLogic\Core\Event\BotKeywordEvent;
use Veiw\BotLogic\Core\Keyword\AbstractKeywordDispatcher;
use Veiw\BotLogic\Presentation\Response\InvalidKeywordResponse;
use Veiw\BotLogic\Presentation\Response\LogicResultResponse;
use Veiw\BotLogic\Presentation\Service\LogicResult;


class ExecuteKeywordTargetSubscriber implements EventSubscriberInterface
{
    use TraitContainer;

    static public function getSubscribedEvents()
    {
        return [
            BotEvent::BOT_KEYWORD =>  [
                ['executeTarget' , 100] ,
            ]
        ];
    }

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container) ;
    }

    public function executeTarget(BotKeywordEvent $keywordEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        ##
        $keywordTarget = $keywordEvent->getTarget() ;
        $container = $this->getContainer();

        ##
        $logicresult = new LogicResult() ;
        $logicresult->setEvent($keywordEvent);

        ##
        if(is_string($keywordTarget) && (! class_exists($keywordTarget)) )    {
            ##
            $invalidKeyword = new InvalidKeywordResponse() ;
            $invalidKeyword->setPlatform($keywordEvent->getChannel()) ;
            ##
            $logicresult->pushMessage( (string) $invalidKeyword) ;
            ##
            $keywordEvent->setLogicResult($logicresult) ;
            ##
            return new LogicResultResponse($logicresult) ;
        }
        else{

            ## check if container has the TargetKeywordHanlder
            if(! $container->has($keywordTarget))    {
                ##
                $invalidKeyword = new InvalidKeywordResponse() ;
                $invalidKeyword->setPlatform($keywordEvent->getChannel()) ;
                ##
                $logicresult->pushMessage( (string) $invalidKeyword) ;
                ##
                $keywordEvent->setLogicResult($logicresult) ;
                ##
                return new LogicResultResponse($logicresult) ;
            }

            ## get the target as a container service for dependencies injection
            $keywordTarget = $container->get($keywordTarget) ;


        }

       /* if(! is_callable($keywordTarget))    {
            throw new \RuntimeException('A callable object or class is required as option for Keyword target') ;
        }*/

        /**
         * check for AbstractKeywordDispather object
         */
        if($keywordTarget instanceof AbstractKeywordDispatcher)    {
            ##
            $keywordTarget->setParams($keywordEvent->getRouteMatch()) ;
        }

        /**
         * when the KeywordTarget Handler is just an Invokable class or object
         */
        if (! method_exists($keywordTarget , 'dispatch')) {
            ##
            $result_from_keyword_handler = $keywordTarget($keywordEvent) ;
        }
        else{
            ##
            $result_from_keyword_handler = $keywordTarget->dispatch($keywordEvent);
        }

        ##
        if($result_from_keyword_handler instanceof LogicResult)    {
            /**
            * Because the Response Listener will need some value from the initial event executed e.g KeywordTarget, RouterMatch etc
            * we design the event Object to the logic result
            */
            if(null == $result_from_keyword_handler->getEvent())    {
                $result_from_keyword_handler->setEvent($keywordEvent);
            }
            ##
            $keywordEvent->setResponse(new LogicResultResponse($result_from_keyword_handler));
        }
        elseif($result_from_keyword_handler instanceof LogicResultResponse)    {
            /**
             * Because the Response Listener will need some value from the initial event executed e.g KeywordTarget, RouterMatch etc
             * we design the event Object to the logic result
             */
            if(null == $result_from_keyword_handler->getLogicResult()->getEvent())    {
                $result_from_keyword_handler->getLogicResult()->setEvent($keywordEvent);
            }
            ##
            $keywordEvent->setResponse($result_from_keyword_handler);
        }
        elseif($result_from_keyword_handler instanceof Response)    {
            ##
            $keywordEvent->setResponse($result_from_keyword_handler);
        }

    }

}