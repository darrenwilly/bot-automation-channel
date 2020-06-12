<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Presentation\Response;

use Veiw\BotLogic\Presentation\Service\LogicResult ;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as parentClass ;

class LogicResultResponse extends parentClass
{
    /**
     * @var LogicResult
     */
    protected $logicResult;

    /**
     * Flags to use with json_encode.
     *
     * @var int
     */
    #protected $jsonFlags = JSON_FORCE_OBJECT | JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT;
    protected $jsonFlags = 79 ;

    /**
     * @param LogicResult $apiProblem
     */
    public function __construct($logicResult)
    {
        ##
        $this->logicResult = $logicResult;

        ## make sure we have a result of LogicResult from here on
        if(! $logicResult instanceof LogicResult)    {
            ## allow the use of JsonResponse
            if(! $logicResult instanceof JsonResponse)    {
                throw new \RuntimeException(sprintf('instance of LogicResult is required but %s given' , gettype($logicResult))) ;
            }
            else{
                return $logicResult ;
            }
        }

        /**
         * because we realise that there might be cases whereby the response required might not just be only string in the response body
         * but a call to an API or a particular resource, so we decide to use callback object which might have power to manipulate the data
         * in the LogicResult itself.
         *
         * this feature can also be used by Schema response type to manipulate the logicResult futher
         */
        if($externalHandleClass = $logicResult->getResponseHandleClass($this))    {
            ## when the LogicResult has the option to use an alterative response handle class e.g Hal, JsonApi or JsonSchema e.t.c
            $body = $externalHandleClass->getBody() ;
            $status = $externalHandleClass->getStatus() ;
            $headers = $externalHandleClass->getHeaders() ;
        }
        else{
            ##
            $status = 200 ;
            ### set a default status code incase the status code cannot be found
            if(method_exists($logicResult , 'getStatus'))    {
                $status =  $logicResult->getStatus();

                if(is_array($status))      {
                    $status = current($status);
                }
                ## still verify that status is not empty
                if(null == $status)    {
                    $status = 200 ;
                }
            }
            elseif($logicResult->isError()){
                $status = 500 ;
            }

            ##
            $body = $this->getContent();
            ##
            $headers = $this->getHeaders() ;
        }
        ##
        parent::__construct($body, $status, $headers);
    }

    public function getLogicResult() : LogicResult
    {
        return $this->logicResult ;
    }

    /**
     * Retrieve the content.
     *
     * Serializes the composed ApiProblem instance to JSON.
     *
     * @return string
     */
    public function getContent()
    {
        ##
        $logicResult = $this->getLogicResult() ;

        ##
        $logicResultContentAsArray = (array) $logicResult->toArray() ;
        $this->jsonFlags = JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR ;

        ## when logicResult is empty and empty result was not allowed to be returned, then throw exception
        if(0 >= count($logicResultContentAsArray) && false === $logicResult->getAllowEmptyResult())    {
            ##
            $exception = new \RuntimeException('Logic might have have executed successfully, but there is problem with response assembling ' , 500) ;
            ##
            $logicResult = new LogicResult($exception) ;


            ##
            $logicResultContentAsArray = $logicResult->toArray() ;
            $logicResultContentAsArray['debug-disector'] = $logicResult->getInitializer() ;

            ##
            $errorBody = json_encode($logicResultContentAsArray , $this->jsonFlags);
            ##
            return $errorBody;
        }
        ##
        return json_encode($logicResultContentAsArray , $this->jsonFlags);
    }

    /**
     * Retrieve headers.
     *
     * Proxies to parent class, but then checks if we have an content-type
     * header; if not, sets it, with a value of "application/problem+json".
     *
     * @return mixed
     */
    public function getHeaders() : array
    {

        $this->headers = [];

        if ($this->getLogicResult() instanceof LogicResult) {
            ##
            $logicResultHeader = $this->getLogicResult()->getHeaders() ;
            ## when error has been activated
            if(0 < count($logicResultHeader))    {
                ##
                foreach ($logicResultHeader as $header_key => $header_item)    {
                    #
                    $this->headers->set($header_key , $header_item) ;
                }
            }
        }

        return $this->headers;
    }

}