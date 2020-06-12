<?php
namespace Veiw\BotLogic ;

use DV\TraitExceptionBase;
use RuntimeException as parentException ;
use Throwable;

class RuntimeException extends parentException
{
    use TraitExceptionBase;

    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        $finalMessage = $this->processMessage($message) ;
        ##
        parent::__construct($finalMessage, $code, $previous);
    }
}