<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Presentation\Service ;

use DV\MicroService\LogicResult as parentClass ;
use DV\MicroService\TraitEvent;


/**
 * Object describing an API-Response payload.
 */
class LogicResult extends parentClass
{
    use TraitEvent;

    public function __construct($options=[])
    {
        ## when options is null, return result object with construction
        if(! empty($options))    {
            parent::__construct($options) ;
        }
    }

    public function pushMessage($message)
    {
        ## remove existing data
        $this->clearDataModel() ;
        ## create a new message
        $this->processString($message) ;
    }

    public function getBody()
    {
        $body = $this->toArray() ;
        ## flateen every array into json string
        if(is_array($body))    {
            $body = json_encode($body) ;
        }
        ##
        return  $body;
    }

    public function flatten($array)
    {   ##
        if (!is_array($array)) {
            // nothing to do if it's not an array
            return [$array];
        }

        $result = [];
        foreach ($array as $value) {
            // explode the sub-array, and add the parts
            $result = array_merge($result, $this->flatten($value));
        }
        ##
        return $result;
    }

    public function getFirstMessage()
    {
        $message = ($this->getDataModel()->getMessage()['content']) ;
        ##
        if(is_array($message))    {
            $message =  current($message) ;
        }
        ##
        return $message[0] ;
    }

    public function getReplyTo()
    {
        return $this->getEvent()->getFrom() ;
    }

}