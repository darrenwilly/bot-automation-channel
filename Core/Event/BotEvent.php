<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Event;

use Veiw\BotLogic\Presentation\Service\LogicResult;
use Symfony\Contracts\EventDispatcher\Event;
use Veiw\BotLogic\TraitName;
use Veiw\BotLogic\TraitResponse ;

abstract class BotEvent extends Event
{
    use TraitResponse ;
    use TraitName ;

    const BOT_REQUEST_EVENT = 'bot.request' ;

    const BOT_SERVICE_PROVIDER = 'bot.service_provider' ;
    const BOT_SERVICE_PROVIDER_RESPONSE = 'bot.service_provider.response' ;

    const BOT_CHANNEL_ADAPTER = 'bot.channel_adapter' ;


    const BOT_COLLATE_KEYWORD = 'bot.collate_keyword' ;

    const BOT_KEYWORD_PRE = 'bot.keyword_pre' ;
    const BOT_KEYWORD = 'bot.keyword' ;
    const BOT_KEYWORD_POST = 'bot.keyword.post' ;

    const BOT_RESPONSE = 'bot.response' ;

    protected $logicResult ;


    public function getLogicResult() : LogicResult
    {
        return $this->logicResult ;
    }
    public function setLogicResult(LogicResult $logicResult)
    {
        $this->logicResult = $logicResult ;
    }
}