<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Presentation\Response;

use Veiw\BotLogic\Core\Keyword\KeywordResponseInterface;
use Veiw\BotVAS\GTSInfoTel\Channel\ShortcodeOptions;

class InvalidKeywordResponse implements KeywordResponseInterface
{
    protected $platform = PROJECT_NAME;

    public function __invoke($options=[])
    {
       return $this->__toString() ;
    }

    public function getPlatform()
    {
        return $this->platform ;
    }

    public function setPlatform($platform)
    {
        $this->platform = $platform ;
    }

    public function __toString()
    {
        ##
        $platform = $this->getPlatform() ;
        ##
        if($platform instanceof ShortcodeOptions)    {
            ##
            $platform = $platform->getChannelName() ;
        }
        ##
        return sprintf('Invalid KEYWORD requested from %s Platform' , $platform ) . PHP_EOL;
    }
}