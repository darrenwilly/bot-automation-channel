<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator ;
use Veiw\BotLogic\Core\DependencyInjection\Routing\AdvancedLoader;

if(isset($container) && (! $container instanceof \Symfony\Component\DependencyInjection\ContainerBuilder) )   {
    trigger_error('invalid container object') ;
    die;
}

/**
 * register the Container
 */
return function(ContainerConfigurator $configurator) use($container) {
    // default configuration for services in *this* file
    $services = $configurator->services();
    /**
     *
     */
    $services->defaults()
        ->autowire()      // Automatically injects dependencies in your services.
        ->autoconfigure() // Automatically registers your services as commands, event subscribers, etc.
        ->public()
    ;

    try{
        /**
         * Point to call default symfony service to made public
         */
        ## autoload the extra configuration for the module
        #$configurator->import(__DIR__.'/autoload/*.php');
        ## set the parameters
        $configurator->parameters()->set('bac.bundle.dir', dirname(dirname(dirname(__DIR__))));

        /**
         * GLOBAL ROUTER RELATED SERVICE
         * Register Advanced Router
         * This load is the one that will interate all Bundle available and load htem on the fly
         */
        $services->set(AdvancedLoader::class)->tag('routing.loader')->autowire()->autoconfigure()->public() ;

        /**
         * REGISTER CONTROLLERS
         */
        $services->set(\Veiw\BotLogic\Presentation\RequestHandler\RequestLogicOperation::class)->tag('controller.service_arguments');

        /**
         * Register every necessary aggregator
         */
        $services->set(\Veiw\BotLogic\Core\Channel\ChannelAdapterAggregator::class) ;
        $services->set(\Veiw\BotLogic\Core\ServiceProvider\ServiceProviderAggregator::class) ;
        $services->set(\Veiw\BotLogic\Core\ServiceProvider\ServiceProviderResponseAggregator::class) ;
        $services->set(\Veiw\BotLogic\Core\Keyword\KeywordRouteAggregator::class) ;

        /**
         * Register the Channel Base Services
         */
        $services->set(\Veiw\BotLogic\Core\Channel\Email::class) ;
        $services->set(\Veiw\BotLogic\Core\Channel\Fbm::class) ;
        $services->set(\Veiw\BotLogic\Core\Channel\Shortcode::class) ;
        $services->set(\Veiw\BotLogic\Core\Channel\Telegram::class) ;
        $services->set(\Veiw\BotLogic\Core\Channel\Ussd::class) ;
        $services->set(\Veiw\BotLogic\Core\Channel\Whatsapp::class) ;

        /**
         *  Hook into the Response Event to modify the response into Pattern designated by serviceProvider if any
         */
        $services->set(\Veiw\BotLogic\Presentation\EventSubscriber\LogicResultServiceDetectorInResponseSubscriber::class)->tag('kernel.response') ;
        $services->set(\Veiw\BotLogic\Core\EventSubscriber\BotServiceProvider\ExecuteServiceProviderResponseSubscriber::class)->tag('kernel.response') ;

        /**
         * Register the default EventListener & Subscriber
         */
        $services->set(\Veiw\BotLogic\Core\EventSubscriber\BotRequest\ValidateRequestUriFromServiceProviderSubscriber::class)->tag(\Veiw\BotLogic\Core\Event\BotEvent::BOT_REQUEST_EVENT) ;

        $services->set(\Veiw\BotLogic\Core\EventSubscriber\BotCollateKeyword\DefaultKeywordCollatorSubscriber::class)->tag(\Veiw\BotLogic\Core\Event\BotEvent::BOT_COLLATE_KEYWORD) ;
        $services->set(\Veiw\BotLogic\Core\EventSubscriber\BotCollateKeyword\LocateKeywordFromContentSubscriber::class)->tag(\Veiw\BotLogic\Core\Event\BotEvent::BOT_COLLATE_KEYWORD) ;
        $services->set(\Veiw\BotLogic\Core\EventSubscriber\BotCollateKeyword\InvalidKeywordSubscriber::class)->tag(\Veiw\BotLogic\Core\Event\BotEvent::BOT_COLLATE_KEYWORD) ;

        $services->set(\Veiw\BotLogic\Core\EventSubscriber\BotKeyword\ExecuteKeywordTargetSubscriber::class)->tag(\Veiw\BotLogic\Core\Event\BotEvent::BOT_KEYWORD) ;
        $services->set(\Veiw\BotLogic\Core\EventSubscriber\BotKeyword\StopKeywordTargetExecutionSubscriber::class)->tag(\Veiw\BotLogic\Core\Event\BotEvent::BOT_KEYWORD) ;

        /**
         * Register Keyword RootStack Class
         */
        $services->set(\Veiw\BotLogic\Core\Keyword\RootStack\RootInterface::class , \Veiw\BotLogic\Core\Keyword\RootStack\ShortcodeRoot::class) ;
        $services->set(\Veiw\BotLogic\Core\Keyword\RootStack\ShortcodeRoot::class) ;
        $services->set(\Veiw\BotLogic\Core\Keyword\RootStack\UssdRoot::class) ;
        $services->set(\Veiw\BotLogic\Core\Keyword\RootStack\WhatsappRoot::class) ;
        $services->set(\Veiw\BotLogic\Core\Keyword\RootStack\TelegramRoot::class) ;
        $services->set(\Veiw\BotLogic\Core\Keyword\RootStack\FBMRoot::class) ;



    }
    catch (\Throwable $exception)   {
        ##
        dump($exception);exit;
    }
    ##
    return $services;
};
