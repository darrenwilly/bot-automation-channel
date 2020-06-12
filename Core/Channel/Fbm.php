<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Channel;


class Fbm extends BaseAbstractAdapter
{
    protected $name = CHANNEL_FACEBOOK_MESSENGER ;
    /**
     * can be used to set the bodyLength of a response but when it is zero means it has not restriction
     */
    protected $bodyLength = 0 ;

}