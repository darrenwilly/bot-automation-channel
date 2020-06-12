<?php
namespace Veiw\BotLogic\Core\EventSubscriber\BotCollateKeyword;

use DV\ContainerService\ServiceLocatorFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Veiw\BotLogic\Core\Event\BotCollateKeywordEvent;
use Veiw\BotLogic\Core\Event\BotEvent;
use Psr\Container\ContainerInterface;


class DefaultKeywordCollatorSubscriber implements EventSubscriberInterface
{

    protected $container ;

    static public function getSubscribedEvents()
    {
        return [
            BotEvent::BOT_COLLATE_KEYWORD =>  [
                ['collateServiceProviderDataFragment' , 1000] ,
            ]
        ];
    }

    public function collateServiceProviderDataFragment(BotCollateKeywordEvent $collateKeywordEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        $requestEvent = $collateKeywordEvent->getRequestEvent() ;
        $request = $requestEvent->getRequest() ;
        ##
        $serviceProviderClass = $requestEvent->getServiceProvider() ;
        ##
        $spChannelAdapterOptions = $requestEvent->getChannel() ;

        ##
        $requestAttr = ['attr'] ;

        ServiceLocatorFactory::setRequest($request);
        $requestParams = ServiceLocatorFactory::getParameters([] , $requestAttr , true );

        ##
        if($requestParams->offsetExists($spChannelAdapterOptions->getToKeyIdentifier()))    {
            $collateKeywordEvent->setTo($requestParams->get($spChannelAdapterOptions->getToKeyIdentifier())) ;
        }

        if($requestParams->offsetExists($spChannelAdapterOptions->getFromKeyIdentifier()))    {
            $collateKeywordEvent->setFrom($requestParams->get($spChannelAdapterOptions->getFromKeyIdentifier())) ;
        }

        if($requestParams->offsetExists($spChannelAdapterOptions->getContentKeyIdentifier()))    {
            $collateKeywordEvent->setContent($requestParams->get($spChannelAdapterOptions->getContentKeyIdentifier())) ;
        }

        $collateKeywordEvent->setChannel($spChannelAdapterOptions) ;
        $collateKeywordEvent->setServiceProvider($serviceProviderClass) ;

    }

}