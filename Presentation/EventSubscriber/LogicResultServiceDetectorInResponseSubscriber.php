<?php
declare(strict_types=1);
namespace Veiw\BotLogic\Presentation\EventSubscriber;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Veiw\BotLogic\Presentation\Response\LogicResultResponse;
use Veiw\BotLogic\Presentation\Service\LogicResult;
use Psr\Container\ContainerInterface;


class LogicResultServiceDetectorInResponseSubscriber implements EventSubscriberInterface
{

    protected $container ;

    static public function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE =>  [
                ['onKernelResponse' , 999] ,
            ]
        ];
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container ;
    }

    /**
     * Detect Logic Result and auto convert to LogicResultResponse class
     * @param ResponseEvent $responseEvent
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onKernelResponse(ResponseEvent $responseEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        /**
         * Fetch the response that is been returned
         */
        $response = $responseEvent->getResponse() ;

        ## check to see if the kernel returned response is equal to LogicResult Service, then convert it to recognized Symfony Response using the LogicResultResponse class
        if($response instanceof LogicResult)    {
            ## convert it back to logicResultResult which will recognized it as symfony response
            $convertedResponse = new LogicResultResponse($response) ;
            ##
            $responseEvent->setResponse($convertedResponse);
        }
    }

}