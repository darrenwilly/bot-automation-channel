<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Keyword;

use DV\MicroService\TraitContainer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Veiw\BotLogic\Core\Event\BotKeywordEvent;

abstract class AbstractKeywordDispatcher
{
    use TraitContainer;

    protected $params;

    public function getRouteMatch()
    {
        return $this->getParams();
    }
    public function getParams()
    {
        return $this->params ;
    }
    public function setParams($params)
    {
        $this->params = $params ;
    }

    abstract public function dispatch(BotKeywordEvent $collateKeywordEvent) ;

}