<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Core\Keyword\RootStack;

use Veiw\BotLogic\Core\Event\BotKeywordEvent;
use Veiw\BotLogic\Core\Keyword\AbstractKeywordDispatcher;
use Veiw\BotLogic\Presentation\Response\InvalidKeywordResponse;
use Veiw\BotLogic\Presentation\Service\LogicResult;


class ShortcodeRoot extends AbstractKeywordDispatcher
{

    public function dispatch(BotKeywordEvent $keywordEvent)
    {
        $invalidKeywordResponse = new InvalidKeywordResponse() ;
        $invalidKeywordResponse->setPlatform($keywordEvent->getChannel()->getChannelName()) ;

        $result = new LogicResult((string) $invalidKeywordResponse) ;
        return $result;
    }
}