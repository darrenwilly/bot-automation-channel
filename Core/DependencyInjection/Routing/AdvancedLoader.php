<?php

namespace Veiw\BotLogic\Core\DependencyInjection\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class AdvancedLoader extends Loader
{

    public function load($resource, string $type = null)
    {
        ## initiate a new routeCollection
        $routes = new RouteCollection();
        ## the core Bundle is a must load for us so we hardcode the logic and make sure it is always loaded
        $core_resource = '@BACBundle/Core/Resources/config/route.web.php';
        $type = 'php';
        ##
        $importedRoutes = $this->import($core_resource, $type);
        ##
        $routes->addCollection($importedRoutes);

        return $routes;
    }

    public function supports($resource, string $type = null)
    {
        return 'advanced_extra' === $type;
    }

}