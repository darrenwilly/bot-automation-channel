<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Event ;

use Symfony\Component\HttpFoundation\Request;
use Veiw\BotLogic\Core\ServiceProvider\ServiceProviderChannelInterface;
use Veiw\BotLogic\Core\ServiceProvider\ServiceProviderExtensionInterface;

final class BotRequestEvent extends BotEvent
{
    protected $request ;
    protected $channel ;
    protected $serviceProvider ;

    public function __construct(Request $request)
    {
        $this->request = $request ;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getServiceProvider() : ServiceProviderExtensionInterface
    {
        return $this->serviceProvider ;
    }
    public function setServiceProvider(ServiceProviderExtensionInterface $serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;
    }

    public function getChannel() : ServiceProviderChannelInterface
    {
        return $this->channel ;
    }
    public function setChannel($channel)
    {
        $this->channel = $channel ;
    }
}
