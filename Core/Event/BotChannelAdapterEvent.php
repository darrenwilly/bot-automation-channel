<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Event ;

use Symfony\Component\HttpKernel\Event\RequestEvent;

final class BotChannelAdapterEvent extends BotEvent
{
    protected $requestEvent ;
    protected $channel ;

    public function __construct(RequestEvent $requestEvent)
    {
        $this->requestEvent = $requestEvent ;
    }

    public function getRequestEvent()
    {
        return $this->requestEvent ;
    }

    public function getChannel()
    {
        return $this->channel ;
    }
    public function setChannel($channel)
    {
        $this->channel = $channel ;
    }
}
