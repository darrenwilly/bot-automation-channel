<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Channel;


class Telegram extends BaseAbstractAdapter
{
    protected $name = CHANNEL_TELEGRAM ;
    /**
     * can be used to set the bodyLength of a response but when it is zero means it has not restriction
     */
    protected $bodyLength = 0 ;

}