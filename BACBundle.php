<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic ;

use Veiw\BotLogic\Core\DependencyInjection\BACExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Veiw\BotLogic\Core\DependencyInjection\Compiler\ChannelAdapterOptionsPass;
use Veiw\BotLogic\Core\DependencyInjection\Compiler\KeywordRouteCollatorPass;
use Veiw\BotLogic\Core\DependencyInjection\Compiler\ServiceProviderExtensionPass;
use Veiw\BotLogic\Core\DependencyInjection\Compiler\ServiceProviderResponsePass;


class BACBundle extends Bundle
{

    public function boot()
    {
        parent::boot() ;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container) ;

        /**
         * You can load Compiler pass on the method
         * $container->addCompilerPass(new SerializerConfigurationPass());
            $container->addCompilerPass(new ConfigurationCheckPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -10);
         */
        $container->addCompilerPass(new ChannelAdapterOptionsPass());
        $container->addCompilerPass(new ServiceProviderExtensionPass());
        $container->addCompilerPass(new ServiceProviderResponsePass());
        $container->addCompilerPass(new KeywordRouteCollatorPass());
    }

    public function getContainerExtension()
    {
        return new BACExtension() ;
    }

    public function shutdown()
    {
    }

}
