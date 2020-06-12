<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Presentation\Response;

use Veiw\BotLogic\Core\Keyword\KeywordResponseInterface;

class TextKeywordResponse implements KeywordResponseInterface
{
    protected $text ;

    public function __construct($options)
    {
        $text = '' ;

       if(is_array($options) && isset($options['text'])) {
            ##
           $text = $options['text'] ;
       }else{
           $text = $options ;
       }
       ##
        $this->text = $text ;
    }

    public function getText()
    {
        return $this->text ;
    }

    public function __toString()
    {
        return sprintf('%s' , $this->text ) . PHP_EOL;
    }
}