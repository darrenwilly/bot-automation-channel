<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Event ;

final class BotCollateKeywordEvent extends BotEvent
{
    protected $requestEvent ;
    protected $to ;
    protected $from ;
    protected $content ;
    protected $channel ;
    protected $serviceProvider ;
    protected $routeMatch ;
    protected $target ;

    public function __construct(BotRequestEvent $requestEvent)
    {
        $this->requestEvent = $requestEvent ;
    }

    public function getRequestEvent()
    {
        return $this->requestEvent;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     * @return BotCollateKeywordEvent
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     * @return BotCollateKeywordEvent
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return BotCollateKeywordEvent
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     * @return BotCollateKeywordEvent
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getServiceProvider()
    {
        return $this->serviceProvider;
    }

    /**
     * @param mixed $serviceProvider
     * @return BotCollateKeywordEvent
     */
    public function setServiceProvider($serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRouteMatch()
    {
        return $this->routeMatch;
    }

    /**
     * @param mixed $routeMatch
     * @return BotCollateKeywordEvent
     */
    public function setRouteMatch($keywordIndex)
    {
        $this->routeMatch = $keywordIndex;
        return $this;
    }

    public function setTarget($target)
    {
        $this->target = $target ;
    }
    public function getTarget()
    {
        return $this->target ;
    }


}
