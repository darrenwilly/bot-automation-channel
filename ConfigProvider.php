<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Veiw\BotLogic\Core\Channel\Email;
use Veiw\BotLogic\Core\Channel\Fbm;
use Veiw\BotLogic\Core\Channel\Shortcode;
use Veiw\BotLogic\Core\Channel\Telegram;
use Veiw\BotLogic\Core\Channel\Ussd;
use Veiw\BotLogic\Core\Channel\Whatsapp;
use Veiw\BotLogic\Keyword\Channel;
use Veiw\BotLogic\KeywordStackLogic\AdStack\AdShortcodeLogic;
use Veiw\BotLogic\KeywordStackLogic\EmStack\EmShortcodeLogic;
use Veiw\BotLogic\KeywordStackLogic\JobStack\JobShortcodeLogic;
use Veiw\BotLogic\KeywordStackLogic\RootStack\Root;
use Veiw\BotLogic\Platform\Listener\MvcKeywordOperationProcessModelListener;
use Veiw\BotLogic\Platform\Listener\MvcOperationRequestPreListener;
use Veiw\BotLogic\Middleware\RequestLogicOperation;
use Veiw\BotLogic\Middleware\RequestLogicOperationFactory;
use Veiw\BotLogic\Middleware\ValidateRequestUri;
use Veiw\BotLogic\Middleware\ValidateRequestUriFactory;
use Veiw\BotLogic\ServiceProvider\ServiceProviderResponseInterface;
use Veiw\BotLogic\ServiceProvider\Shortcode\GTSInfoTel;
use Veiw\BotLogic\ServiceProvider\Shortcode\GTSInfoTelFactory;
use Veiw\BotLogic\ServiceProvider\Shortcode\ServiceProviderResponseFactory;
use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\EventManager\EventManagerInterface;

class ConfigProvider
{

    public function bootstrap(ContainerInterface $container , ServerRequestInterface $request)
    {
        ##
        $eventManager = $request->getAttribute(EventManagerInterface::class) ;

        ##
        if(! $eventManager instanceof EventManagerInterface)    {
            return ;
        }

        /**
         * call this operation on MiddlewarePipeline
         */
        $mvcOperationRequestPreListener = $container->get(MvcOperationRequestPreListener::class) ;
        $mvcOperationRequestPreListener->setRequest($request) ;
        $mvcOperationRequestPreListener->attach($eventManager) ;

        /**
         * call this operation on Middleware Pipeline too
         */
        $mvcKeywordOperationRequestPreListener = $container->get(MvcKeywordOperationProcessModelListener::class) ;
        $mvcKeywordOperationRequestPreListener->setRequest($request) ;
        $mvcKeywordOperationRequestPreListener->attach($eventManager) ;
    }

    public function __invoke() : array
    {
        ##
        $botConfig = [] ;

        ##
        if($internalConfig = $this->getConfig())    {
            ##
            $botConfig = array_merge_recursive($botConfig , $internalConfig) ;
        }

        $dConfig = [
            'dependencies' => $this->getDependencies(),
        ];

        ##
        return array_merge_recursive($botConfig , $dConfig) ;
    }

    public function getConfig()
    {
        #return include __DIR__ . '/../config/doctrine.config.php' ;
        $loader = [new PhpFileProvider(realpath(dirname(__DIR__)) . '/config/autoload/{{,*.}global,{,*.}local}.php')];
        ##
        return (new \Zend\ConfigAggregator\ConfigAggregator($loader))->getMergedConfig() ;
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'aliases' => [
                ## Channel Adapter
                CHANNEL_SMS_SHORTCODE => Shortcode::class ,
                CHANNEL_USSD => Ussd::class ,
                CHANNEL_WHATSAPP => Whatsapp::class ,
                CHANNEL_TELEGRAM => Telegram::class ,
                CHANNEL_FACEBOOK_MESSENGER => Fbm::class ,
                CHANNEL_EMAIL => Email::class ,

                ## Service Provider
                'gtsinfotel' => GTSInfoTel::class
            ] ,
            'invokables' => [
                ##
                Shortcode::class => Shortcode::class ,
                Ussd::class => Ussd::class ,
                Whatsapp::class => Whatsapp::class ,
                Fbm::class => Fbm::class ,
                Email::class => Email::class ,
                Telegram::class => Telegram::class
            ] ,
            'factories'  => [
                ##
                MvcOperationRequestPreListener::class => MvcOperationRequestPreListener::class ,
                MvcKeywordOperationProcessModelListener::class => MvcKeywordOperationProcessModelListener::class ,

                ##
                ConfigAggregator::class => ConfigAggregator::class ,

                ##
                ValidateRequestUri::class => ValidateRequestUriFactory::class ,
                RequestLogicOperation::class => RequestLogicOperationFactory::class ,

                ## Service Provider
                ServiceProviderResponseInterface::class => ServiceProviderResponseFactory::class ,
                GTSInfoTel::class => GTSInfoTelFactory::class,

                ## KeywordLogic
                Root::class => Root::class ,
                JobShortcodeLogic::class => JobShortcodeLogic::class,
                EmShortcodeLogic::class => EmShortcodeLogic::class ,
                AdShortcodeLogic::class => AdShortcodeLogic::class
            ],

        ];
    }
}