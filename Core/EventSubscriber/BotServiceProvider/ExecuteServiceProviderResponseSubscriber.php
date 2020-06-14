<?php
declare(strict_types=1);
namespace Veiw\BotLogic\Core\EventSubscriber\BotServiceProvider;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Veiw\BotLogic\Core\Event\BotEvent;
use Veiw\BotLogic\Core\Event\BotKeywordEvent;
use Veiw\BotLogic\Core\ServiceProvider\ServiceProviderResponseAggregator;
use Psr\Container\ContainerInterface;
use Veiw\BotLogic\Presentation\Response\LogicResultResponse;
use Veiw\BotLogic\Presentation\Service\LogicResult;

/**
 * This class should have been created as Event in the RequestLogicOperation but I decide to make it a Listener to Response so that it can capture
 * response object sent from any part of the application and do auto conversion
 *
 * Class ExecuteServiceProviderResponseSubscriber
 * @package Veiw\BotLogic\Core\EventSubscriber
 */
class ExecuteServiceProviderResponseSubscriber implements EventSubscriberInterface
{
    protected $container ;

    static public function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE =>  [
                ['triggerServiceProviderResponse' , 1000] ,
            ]
        ];
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container ;
    }

    public function triggerServiceProviderResponse(ResponseEvent $responseEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        $container = $this->container ;
        $response = $responseEvent->getResponse() ;


        $request = $responseEvent->getRequest() ;


        ## make sure that the ServiceProviderResponse Aggregator is set
        if(! $container->has(ServiceProviderResponseAggregator::class))    {
            return ;
        }

        /*
         * Only instance of LogicResult & LogicResultResponse can be mutated .
         */
        if(!$response instanceof LogicResultResponse && ! $response instanceof LogicResult)    {
            return ;
        }

        if($response instanceof LogicResultResponse)    {
            $logicResult = $response->getLogicResult() ;
        }else{
            $logicResult = $response ;
        }

        /**
         * Don't bother call the ServiceProvider Response in Development Local Mode
         */
        if(in_array($request->server->get('REMOTE_ADDR') , ['127.0.0.1' , 'locahost']) ||
            (false !== strpos($request->server->get('REMOTE_ADDR') ,'192.168.')))    {
            ##
            $response = new Response($logicResult->getFirstMessage()) ;
            $responseEvent->setResponse($response) ;
            ##
            return ;
        }

        /**
         * Fetch the triggerEvent Of LogicResult which will definitely have RequestParams
         */
        $eventThatCallLogicResult = $this->extractChannelAndServiceProviderFromEvent($logicResult->getEvent()) ;

        if(! $eventThatCallLogicResult)    {
            return ;
        }

        ##
        $serviceProviderResponseAggregator = $container->get(ServiceProviderResponseAggregator::class) ;

        /**
         * Call the Service Provider Response Aggregator and call the execute method here
         */
        $serviceProviderResponse = $serviceProviderResponseAggregator->getResponse($eventThatCallLogicResult['channel'] , $eventThatCallLogicResult['serviceProvider']) ;
        ## check and make sure that a Response of the request Adapter & ServiceProvider has been attached
        if(null == $serviceProviderResponse)    {
            return;
        }

        ##
        if(! method_exists($serviceProviderResponse , 'execute'))    {
            return ;
        }

        try{
            /**
             * Call the Service Provider Response Class and call his execute methods which has the opportunity to manipulate the response Object
             */
            $response = call_user_func([$serviceProviderResponse , 'execute'] , $responseEvent) ;
            ##
            if($response instanceof Response)    {
                ##
                $responseEvent->setResponse($response) ;
            }
        }
        catch (\Throwable $exception)       {
            /**
             * Log an error and trigger an Email event to the Developer
             */
        }

    }

    private function extractChannelAndServiceProviderFromEvent($event)
    {
        if(! $event instanceof BotEvent)    {
            ##
            return false;
        }
        ##
        return ['channel' => $event->getChannel()->getChannelName() , 'serviceProvider' => $event->getServiceProvider()->getName()] ;
    }


}