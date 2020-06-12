<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Event ;


final class BotServiceProviderEvent extends BotEvent
{
    protected $requestEvent ;
    protected $serviceProvider ;

    public function __construct(BotRequestEvent $botRequestEvent)
    {
        $this->requestEvent = $botRequestEvent ;
    }

    public function getRequestEvent()
    {
        return $this->requestEvent ;
    }

    public function getServiceProvider()
    {
        return $this->getRequestEvent()->getServiceProvider() ;
    }

}
