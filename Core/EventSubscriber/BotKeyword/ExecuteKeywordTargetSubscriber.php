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

    const DEFAULT_METHOD_TO_CALL = 'dispatch' ;

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

        ## set the default method to call
        $method_to_call = static::DEFAULT_METHOD_TO_CALL ;

        /**
         * Since the major callback to set for each route is string, so we decided to test for it first
         */
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

            /**
             * This solve the issue when the callback set for Keyword Route is a call back
             */
            if(is_callable($keywordTarget) && is_array($keywordTarget))    {
                ##
                $class_to_call = $keywordTarget[0] ;
                $method_to_call = $keywordTarget[1] ;
                ##
                $keywordTarget = $class_to_call ;
            }

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
        if (! method_exists($keywordTarget , $method_to_call) && method_exists($keywordTarget , '__invoke')) {
            ##
            $result_from_keyword_handler = $keywordTarget($keywordEvent) ;
        }
        else{
            /**
             * Solve the issue with Static method call for Keyword Targets
             */
            $reflector = new \ReflectionMethod($keywordTarget, $method_to_call);

            ##
            if($reflector->isStatic()) {
                ##
                $result_from_keyword_handler = call_user_func([$keywordTarget , $method_to_call] , $keywordEvent) ;
            }
            else{
                ##
                $result_from_keyword_handler = $keywordTarget->{$method_to_call}($keywordEvent);
            }
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