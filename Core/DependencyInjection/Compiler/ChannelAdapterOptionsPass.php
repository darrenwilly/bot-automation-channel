<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Veiw\BotLogic\Core\Channel\ChannelAdapterAggregator;

class ChannelAdapterOptionsPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        ## always first check if the primary service is defined
        if (! $container->has(ChannelAdapterAggregator::class)) {
            return;
        }

        $definition = $container->findDefinition(ChannelAdapterAggregator::class);

        ## find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds(ChannelAdapterAggregator::SERVICE_TAG_ALIAS);

        if(null == count($taggedServices))    {
            return ;
        }

        foreach ($taggedServices as $id => $tags) {
            ## add the transport service to the TransportChain service
            $service_provider_extension_object = new Reference($id) ;
            ##
            $definition->addMethodCall('addChannelOptions', [$service_provider_extension_object]);
        }
    }
}