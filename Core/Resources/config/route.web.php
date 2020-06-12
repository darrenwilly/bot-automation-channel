<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Veiw\BotLogic\Presentation\RequestHandler\RequestLogicOperation;

$router = function (RoutingConfigurator $routes) {
    /**
     * Route for SMS Backend
     * https://emvas.emplug.com/bot-automation/shortcode/gtsinfotel/apijson/5825680155
     * https://tap.emplug.com/bot-automation/shortcode/gtsinfotel/apijson/2035621256
     */
    $routes->add('vas.endpoint' , '/bot-automation/{channelAdapter}/{serviceProvider}/{datatype}/{apiToken}')
            ->controller(RequestLogicOperation::class);
};

return $router ;