<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Channel ;


trait TraitChannelAdapter
{
    /**
     * @var BaseAbstractAdapter
     */
    protected $channelAdapter ;

    public function setChannelAdapter($channelAdapter)
    {
        $this->channelAdapter = $channelAdapter ;
    }

    /**
     * @return BaseAbstractAdapter
     */
    public function getChannelAdapter()
    {
        return $this->channelAdapter ;
    }
}