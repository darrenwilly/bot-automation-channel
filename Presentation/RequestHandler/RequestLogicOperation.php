<?php
declare(strict_types=1);
namespace Veiw\BotLogic\Presentation\RequestHandler;

use DV\Mvc\Controller\ActionController;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Veiw\BotLogic\Core\Event\BotCollateKeywordEvent;
use Veiw\BotLogic\Core\Event\BotEvent;
use Veiw\BotLogic\Core\Event\BotKeywordEvent;
use Veiw\BotLogic\Core\Event\BotRequestEvent;
use Veiw\BotLogic\Core\Event\BotServiceProviderEvent;
use Veiw\BotLogic\Presentation\Response\LogicResultResponse;
use Veiw\BotLogic\Presentation\Service\LogicResult;


class RequestLogicOperation extends ActionController
{

    protected $container ;

    public function __invoke(EventDispatcherInterface $dispatcher , Request $request)
    {
      try{
          /**
           * Call all necessary request validation at this point
           */
          $requestEvent = new BotRequestEvent($request) ;
          ##
          $dispatcher->dispatch($requestEvent , BotEvent::BOT_REQUEST_EVENT);

          /**
           * Please note that every listerner to the event must return a Response Object and once it is returned, the script can stop execution immediately
           */
          if($requestEvent->hasResponse())    {
              ## detect the response time set and assign the event that trigger it
              return $requestEvent->getResponse() ;
          }

          /**
           * Time to call the service Provider event and detect
            */
          $serviceProviderEvent = new BotServiceProviderEvent($requestEvent) ;
          ##
          $dispatcher->dispatch($serviceProviderEvent , BotEvent::BOT_SERVICE_PROVIDER) ;


          /**
           * Time to call the Channel Adapter event and detect which adapter is been used
           */
          $collateKeywordEvent = new BotCollateKeywordEvent($requestEvent) ;
          ##
          $dispatcher->dispatch($collateKeywordEvent , BotEvent::BOT_COLLATE_KEYWORD) ;
          /**
           * Please note that every listerner to the event must return a Response Object and once it is returned, the script can stop execution immediately

          if($channelAdapterEvent->hasResponse())    {
              return $channelAdapterEvent->getResponse() ;
          }

          /**
           * Time to call the Keyword Pre event without checking for result
           */
          $keywordEvent = new BotKeywordEvent($collateKeywordEvent) ;

          $keywordPreEvent = clone $keywordEvent ;
          ##
          $dispatcher->dispatch($keywordPreEvent , BotEvent::BOT_KEYWORD_PRE) ;

          /**
           * Dispatch again for KeywordEvent and check for response
           */
          $dispatcher->dispatch($keywordEvent , BotEvent::BOT_KEYWORD) ;

          /**
           * Trigger KeywordPost Event without checking for result
           */
          $keywordPostEvent = clone $keywordPreEvent ;
          $dispatcher->dispatch($keywordPostEvent , BotEvent::BOT_KEYWORD_POST) ;

          /**
           * Please note that every listerner to the event must return a Response Object and once it is returned, the script can stop execution immediately
           */
          if($keywordEvent->hasResponse())    {
              return $keywordEvent->getResponse() ;
          }

          ## if the Listerner has not set the Response Object, then fetch response which is likely to be LogicResult from KeywordEvent and returned it instead
          if(null == $keywordEvent->getLogicResult()->getEvent())  {
              ##
              $keywordEvent->getLogicResult()->setEvent($keywordEvent) ;
          }
          ##
          return new LogicResultResponse($keywordEvent->getLogicResult()) ;

      }
      catch (\Throwable $exception)    {
            ##
            $logicResult = new LogicResult($exception) ;
            ##
            return new LogicResultResponse($logicResult) ;
      }

        /**
         * Render a default Dashboard of throw an exception if any dashboard cannot be found
         */
        $finalUnknownError = new \RuntimeException('No Dashboard listener is attached to the Dashboard.action listener') ;

        $logicResult = new LogicResult($finalUnknownError) ;

        return new LogicResultResponse($logicResult) ;
    }


}