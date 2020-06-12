<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Core\Channel;

interface ChannelAdapterInterface
{
    public function getContent() ;
    public function setContent($content) ;
    public function getBodyLength() ;
    public function setBodyLength($bodyLength) ;
    public function getResult() ;
    public function setResult($result) ;
}