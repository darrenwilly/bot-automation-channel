<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\EventDispatcher\DependencyInjection\AddEventAliasesPass;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Veiw\BotLogic\Core\Event\BotCollateKeywordEvent;
use Veiw\BotLogic\Core\Event\BotEvent;
use Veiw\BotLogic\Core\Event\BotKeywordEvent;
use Veiw\BotLogic\Core\Event\BotRequestEvent;
use Veiw\BotLogic\Core\Event\BotServiceProviderEvent;
use Veiw\BotLogic\Core\ServiceProvider\ServiceProviderResponseInterface;

/**
 * This is the class that loads and manages DVDoctrineBundle configuration.
 *
 * @author DarrenTrojan <darren.willy@gmail.com>
 */
class BACExtension extends Extension implements PrependExtensionInterface , CompilerPassInterface
{

    public function prepend(ContainerBuilder $container): void
    {
        ## fetch all the extension
        $extension = $container->getExtensions() ;

    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        try {
            ## load the bootstrap file
            $bootstrap = dirname(dirname(__DIR__)) . '/bootstrap.php';
            ##
            if(! file_exists($bootstrap))    {
                throw new \RuntimeException('bootstrap file is required to initialized the Bundle for interopability purpose') ;
            }
            ##
            require $bootstrap ;

            /*$container->registerForAutoconfiguration(ServiceProviderResponseInterface::class)
                                                        ->addTag('bac.service_provider_response');*/

            ##
            $locator = new FileLocator(dirname(__DIR__) . '/Resources/config/');
            ##
            $loader = new PhpFileLoader($container, $locator);
            ##
            $loader->load('services.php', 'php');



        } catch (\Throwable $exception) {
            #var_dump($exception->getMessage() . '<br>'. $exception->getTraceAsString()); exit;
        }
    }

    public function getAlias()
    {
        return 'bac' ;
    }

    public function process(ContainerBuilder $container)
    {
        try {
            ##
           /* $container->addCompilerPass(new ChannelAdapterOptionsPass());
            $container->addCompilerPass(new ServiceProviderExtensionPass());
            $container->addCompilerPass(new ServiceProviderResponsePass());*/

            /**
             * This was suppose to create an alias name for the current event so that it's listener can attach to it through it's name
             */
            $container->addCompilerPass(new AddEventAliasesPass([BotRequestEvent::class => BotEvent::BOT_REQUEST_EVENT], BotEvent::BOT_REQUEST_EVENT));
            $container->addCompilerPass(new AddEventAliasesPass([BotServiceProviderEvent::class => BotEvent::BOT_SERVICE_PROVIDER], BotEvent::BOT_SERVICE_PROVIDER));
            $container->addCompilerPass(new AddEventAliasesPass([BotCollateKeywordEvent::class => BotEvent::BOT_COLLATE_KEYWORD], BotEvent::BOT_COLLATE_KEYWORD));
            $container->addCompilerPass(new AddEventAliasesPass([BotKeywordEvent::class => BotEvent::BOT_KEYWORD], BotEvent::BOT_KEYWORD));
            $container->addCompilerPass(new AddEventAliasesPass([BotKeywordEvent::class => BotEvent::BOT_KEYWORD_PRE], BotEvent::BOT_KEYWORD_PRE));
            $container->addCompilerPass(new AddEventAliasesPass([BotKeywordEvent::class => BotEvent::BOT_KEYWORD_POST], BotEvent::BOT_KEYWORD_POST));

            /**
             * This was suppose to fetch all the service(listerner object) using the above event tag name and collate them
             */
            $container->addCompilerPass(new RegisterListenersPass(
                BotRequestEvent::class, BotEvent::BOT_REQUEST_EVENT, BotEvent::BOT_REQUEST_EVENT, BotEvent::BOT_REQUEST_EVENT), PassConfig::TYPE_BEFORE_REMOVING);
            $container->addCompilerPass(new RegisterListenersPass(
                BotServiceProviderEvent::class, BotEvent::BOT_SERVICE_PROVIDER, BotEvent::BOT_SERVICE_PROVIDER, BotEvent::BOT_SERVICE_PROVIDER), PassConfig::TYPE_BEFORE_REMOVING);
            $container->addCompilerPass(new RegisterListenersPass(
                BotCollateKeywordEvent::class, BotEvent::BOT_COLLATE_KEYWORD, BotEvent::BOT_COLLATE_KEYWORD, BotEvent::BOT_COLLATE_KEYWORD), PassConfig::TYPE_BEFORE_REMOVING);
            $container->addCompilerPass(new RegisterListenersPass(
                BotKeywordEvent::class, BotEvent::BOT_KEYWORD_PRE, BotEvent::BOT_KEYWORD_PRE, BotEvent::BOT_KEYWORD_PRE), PassConfig::TYPE_BEFORE_REMOVING);
            $container->addCompilerPass(new RegisterListenersPass(
                BotKeywordEvent::class, BotEvent::BOT_KEYWORD, BotEvent::BOT_KEYWORD, BotEvent::BOT_KEYWORD), PassConfig::TYPE_BEFORE_REMOVING);
            $container->addCompilerPass(new RegisterListenersPass(
                BotKeywordEvent::class, BotEvent::BOT_KEYWORD_POST, BotEvent::BOT_KEYWORD_POST, BotEvent::BOT_KEYWORD_POST), PassConfig::TYPE_BEFORE_REMOVING);

        }
        catch (\Throwable $exception)        {
            dump($exception);exit;
        }
    }

}
