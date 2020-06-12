<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Event ;

final class BotKeywordEvent extends BotEvent
{
    protected $collateKeywordEvent ;

    public function __construct(BotCollateKeywordEvent $collateKeywordEvent)
    {
        $this->collateKeywordEvent = $collateKeywordEvent ;
    }

    public function getCollatedKeywordEvent() : BotCollateKeywordEvent
    {
        return $this->collateKeywordEvent;
    }

    public function getTarget()
    {
        return $this->getCollatedKeywordEvent()->getTarget() ;
    }

    public function getRouteMatch()
    {
        return $this->getCollatedKeywordEvent()->getRouteMatch() ;
    }

    public function getChannel()
    {
        return $this->getCollatedKeywordEvent()->getChannel() ;
    }

    public function getServiceProvider()
    {
        return $this->getCollatedKeywordEvent()->getServiceProvider() ;
    }

    public function getResultBody()
    {
        return $this->getLogicResult()->getBody() ;
    }

    public function getReplyTo()
    {
        return $this->getCollatedKeywordEvent()->getFrom() ;
    }
}
