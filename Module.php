<?php
namespace AutomationChannel;

use AutomationChannel\Mvc\Listener\MvcOperationRequestPreListener;
use AutomationChannel\Mvc\Listener\SendLogicResultResponseListener;
use AutomationChannel\Mvc\MvcLogicOperation ;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\ResponseSender\SendResponseEvent;

class Module implements ServiceProviderInterface , BootstrapListenerInterface
{
    const VERSION = '1.1.0';

    public function onBootstrap(EventInterface $e)
    {
        $app       = $e->getApplication();
        $eventManager        = $app->getEventManager();
        $serviceManager		 = $app->getServiceManager() ;

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        ## attach to MvcLogicOperationTriggerman RequestPreEvent
        $mvcOperationRequestPreEvent = new MvcOperationRequestPreListener() ;
        $mvcOperationRequestPreEvent->attach($eventManager) ;


        ## loads self define pattern of request Handling
        $mvcEvent = new MvcLogicOperation() ;
        $mvcEvent->attach($eventManager) ;

        ## send response
        $sendResponseListener = $serviceManager->get('SendResponseListener');
        $sendResponseListener->getEventManager()->attach(SendResponseEvent::EVENT_SEND_RESPONSE ,
                                        new SendLogicResultResponseListener(), -490);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [

        ] ;
    }
}
