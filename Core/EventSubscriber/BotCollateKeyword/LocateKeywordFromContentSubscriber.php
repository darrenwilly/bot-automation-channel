<?php
namespace Veiw\BotLogic\Core\EventSubscriber\BotCollateKeyword;

use DV\ContainerService\ServiceLocatorFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Veiw\BotLogic\Core\Event\BotCollateKeywordEvent;
use Veiw\BotLogic\Core\Event\BotEvent;
use Psr\Container\ContainerInterface;
use Veiw\BotLogic\Core\Keyword\KeywordRouteAggregator;


class LocateKeywordFromContentSubscriber implements EventSubscriberInterface
{

    protected $container ;

    static public function getSubscribedEvents()
    {
        return [
            BotEvent::BOT_COLLATE_KEYWORD =>  [
                ['locateKeywordFromExtractedContext' , 999] ,
            ]
        ];
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container ;
    }

    public function locateKeywordFromExtractedContext(BotCollateKeywordEvent $collateKeywordEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        $this->validateRequirement($collateKeywordEvent) ;

        $requestEvent = $collateKeywordEvent->getRequestEvent() ;
        $request = $requestEvent->getRequest() ;
        ##
        $serviceProviderClass = $requestEvent->getServiceProvider() ;
        ##
        $spChannelAdapterOptions = $requestEvent->getChannel() ;

        ##
        $keywordRouteAggregator = $this->container->get(KeywordRouteAggregator::class) ;

        ## fetch indexd from keyword
        $keywordRouteAggregator->collateRouteAsRouter();
        $keywordRouteAggregator->setKeyword($collateKeywordEvent->getContent());
        ##
        $routeMatch = $keywordRouteAggregator->match() ;

        $routeMatch = (array) $keywordRouteAggregator->getRouteMatch() ;

        if(isset($routeMatch['target']))    {
            $collateKeywordEvent->setTarget($routeMatch['target']) ;
            ##
            unset($routeMatch['target']) ;
        }

        if(0 < count($routeMatch))    {
            ##
            $collateKeywordEvent->setRouteMatch($routeMatch) ;
        }

        /**
         * Please note that if no route match is found here, the  then the InvalidKeywordSubscriber Listener will be trigger and fired a Default response
         */
    }

    protected function validateRequirement(BotCollateKeywordEvent $collateKeywordEvent)
    {
        if(null == strlen($collateKeywordEvent->getContent()))    {
            throw new \RuntimeException('Content from service provider must be set before keyword locator can work efficiently') ;
        }
    }

}