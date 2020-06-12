<?php
namespace Veiw\BotLogic\Core\EventSubscriber\BotRequest;

use Laminas\Config\Config;
use Laminas\Config\Reader\Json;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Veiw\BotLogic\Core\Channel\ChannelAdapterAggregator;
use Veiw\BotLogic\Core\Event\BotEvent;
use Veiw\BotLogic\Core\Event\BotRequestEvent;
use Veiw\BotLogic\Core\ServiceProvider\ServiceProviderAggregator;
use Veiw\BotLogic\Presentation\Service\LogicResult;
use Veiw\BotLogic\Presentation\Response\LogicResultResponse ;
use Psr\Container\ContainerInterface;


class ValidateRequestUriFromServiceProviderSubscriber implements EventSubscriberInterface
{

    protected $container ;

    static public function getSubscribedEvents()
    {
        return [
            BotEvent::BOT_REQUEST_EVENT =>  [
                ['validateUrl' , 1000] ,
                ['validateServiceProvider' , 950] ,
                ['assignServiceProviderObject' , 900] ,
                ['assignChannelAdapterObject' , 850] ,
            ]
        ];
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container ;
    }

    public function validateUrl(BotRequestEvent $requestEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        $request = $requestEvent->getRequest() ;

        if(! $request->attributes->has('channelAdapter'))    {
            ##
            $result = new LogicResult(['error' => true , 'message' => 'Request Channel adapter not received from service provider']) ;
        }
        ##
        $channelAdapter = $request->attributes->get('channelAdapter') ;
        /**
         * Verify that the ChannelAdapter provided has a ServiceManager name config
         *
         */
        $container = $this->container ;

        if(! $container->get(ChannelAdapterAggregator::class)->hasChannelOptions(strtolower($channelAdapter)))    {
            ##
            $result = new LogicResult(['error' => true , 'message' => 'Request Channel adapter not received from service provider']) ;
        }

        /**
         * When LogicResult is returned from the Triggered operation and Error is discovered
         * Just returned the LogicResultResponse jejeli
         */
        if(isset($result))    {
            ##
            if($result instanceof LogicResult && $result->isError())  {
                ##
                $requestEvent->setResponse(new LogicResultResponse($result)); ;
            }
        }

        ## continue handle the rest of the pipeline
        return $requestEvent ;
    }


    public function validateServiceProvider(BotRequestEvent $requestEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        $request = $requestEvent->getRequest() ;
        $container = $this->container ;

        ## verify that the request Object hass the ChannelAdapter Key
        if(! $request->attributes->has('serviceProvider'))    {
            ##
            $result = new LogicResult(['error' => true , 'message' => 'The system cannot receive the origin of the request']) ;
            $requestEvent->setResponse(new LogicResultResponse($result));
            return;
        }


        ## verify that the request Object hass the ChannelAdapter Key
        if(! $container->get(ServiceProviderAggregator::class)->has($request->attributes->get('serviceProvider')))   {
            ##
            $result = new LogicResult(['error' => true , 'message' => 'No configured VAS Service Provider container service provided']) ;
            $requestEvent->setResponse(new LogicResultResponse($result));
            return;
        }

        ##
        $serviceProviderName = $request->attributes->get('serviceProvider') ;

        ## initiate the keyword config aggregator to know if the keyword exist
        $serviceProviderConfigFile = APPLICATION_ETC . '/service.provider.api.token.json' ;
        ##
        if(! file_exists($serviceProviderConfigFile))    {
            $result = new LogicResult(['error' => true , 'message' => 'Service Provider Config file is missing']) ;
            $requestEvent->setResponse(new LogicResultResponse($result));
            return;
        }
        ## read
        $serviceProviderConfig  = new Config((new Json())->fromFile($serviceProviderConfigFile) , true) ;

        ## service provider counter
        $serviceProviderConfigFinder = 0 ;

        foreach($serviceProviderConfig as $serviceProvider)     {
            ## if a service provider name can be found
            if($serviceProvider->name === strtolower($serviceProviderName))  {
                ++$serviceProviderConfigFinder ;
                ## stop the loop if found
                break ;
            }
        }

        ## if the service provider name cannot be found
        if(! $serviceProviderConfigFinder)  {
            $result = new LogicResult(['error' => true , 'message' => sprintf('The system is not configured to receive request from %s service provider' , $serviceProviderName)]) ;
            $requestEvent->setResponse(new LogicResultResponse($result));
            return;
        }


        ## verify that the request Object hass the ChannelAdapter Key
        if(! $apiToken = $request->attributes->get('apiToken'))    {
            ##
            $result = new LogicResult(['error' => true , 'message' => 'The system cannot receive the api token from service provider']) ;
            $requestEvent->setResponse(new LogicResultResponse($result));
            return;
        }

        $serviceProviderTokenFinder = 0;

        ## validate the apitoken given to them
        foreach($serviceProviderConfig as $serviceProvider)     {
            ## if a service provider name can be found
            if($serviceProvider->token == $apiToken)  {
                ++$serviceProviderTokenFinder ;
                ## stop the loop if found
                break ;
            }
        }

        ## if the service provider token cannot be found
        if(! $serviceProviderTokenFinder)  {
            $result = new LogicResult(['error' => true , 'message' => 'The system cannot validate the api token from the Service provider']) ;
            $requestEvent->setResponse(new LogicResultResponse($result));
            return;
        }

    }

    public function assignServiceProviderObject(BotRequestEvent $requestEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        $container = $this->container ;
        $request = $requestEvent->getRequest() ;
        ##
        /**
         * set the channelAdapter in the request parameters
         */
        $serviceProviderToUse = $container->get(ServiceProviderAggregator::class)->get($request->attributes->get('serviceProvider')) ;
        ##
        $requestEvent->setServiceProvider($serviceProviderToUse) ;
    }

    public function assignChannelAdapterObject(BotRequestEvent $requestEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        $container = $this->container ;
        $request = $requestEvent->getRequest() ;
        ##
        /**
         * set the channelAdapter in the request parameters
         */
        $serviceProviderChannel = $container->get(ChannelAdapterAggregator::class)->getChannelOptions($request->attributes->get('channelAdapter')) ;
        $serviceProvider = ($requestEvent->getServiceProvider());
        ##
        $requestEvent->setChannel($serviceProviderChannel[$serviceProvider->getName()]) ;
    }

}