<?php
declare(strict_types=1);
namespace Veiw\BotLogic\Core\Channel;


class Shortcode extends BaseAbstractAdapter
{
    public $name = CHANNEL_SMS_SHORTCODE ;
    /**
     * can be used to set the bodyLength of a response but when it is zero means it has not restriction
     */
    protected $bodyLength = 160 ;

}