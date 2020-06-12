<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Channel ;

use DV\MicroService\TraitBodyLength;
use DV\MicroService\TraitContent;
use Veiw\BotLogic\RuntimeException;

trait TraitResult
{
    use TraitBodyLength , TraitContent ;

    /**
     * @var string
     */
    protected $result ;

    public function setResult($result)
    {
        $this->result = $result ;
    }

    /**
     * @return BaseAbstractAdapter
     */
    public function getResult() : string
    {
        ## has ability to collect content use func_get_arg
        if($args = func_get_arg(0)) {
            ##
            $this->result = $args ;
            ##
            unset($args) ;
        }

        ##
        if(method_exists($this , 'setContent'))    {
            ##
            if(is_string($this->result))    {
                ## set the orignal content in full length
                $this->setContent($this->result) ;
            }
            /*elseif ($this->result instanceof Stream)    {
                ##
                $this->setContent($this->result->getContents()) ;
            }*/
            else{
                throw new \Exception('Only String & Stream are allowed') ;
            }
        }

        ## collect content into a local variable
        $result = $this->result ;

        /**
         * at this point we expect the result as a typical string for further manipulation

        if($this->result instanceof Stream)    {
            ## fetch the content back in to string
            $result = $this->result->getContents() ;
            ## close the streams before opening another
            $this->result->close() ;
        }
        ## make result property empty
        unset($this->result) ;
        */
        ## create a new stream
        #$newStream = new Stream('php://memory' , 'wb+') ;

        ## return full content when the bodyLength is zero
        if(isset($this->bodyLength) && $this->bodyLength <= 0)    {
            ## write a new stream output
            #$newStream->write($result) ;
            return $result ;
        }
        else{
            ##
            $cutString = substr($result , 0 , $this->bodyLength) ;
            return $cutString ;
            ## strip the content to the rate of bodylength
            #$newStream->write($cutString) ;
        }

        #$this->result = $newStream ;
        ##
        #return $newStream ;
    }
}