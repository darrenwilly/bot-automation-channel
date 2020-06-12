<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Channel;


class Ussd extends BaseAbstractAdapter
{

    protected $name = CHANNEL_USSD ;
    /**
     * can be used to set the bodyLength of a response but when it is zero means it has not restriction
     */
    protected $bodyLength = 0 ;

    protected $sessionLifetime = 120 ;

}