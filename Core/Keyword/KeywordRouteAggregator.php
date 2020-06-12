<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Keyword;

use Veiw\BotLogic\Infrastructure\Route\KeywordRouter;

class KeywordRouteAggregator
{
    protected $keyword ;
    protected $router ;
    protected $route ;
    protected $routeMatch ;

    const SERVICE_TAG_ALIAS = 'bac.keyword_route_handler' ;


    public function __construct()
    {
        /**
         * Load the default route from the BOT Extension config
         */
        #$extension = $container->getExtension() ;
        $this->router = new KeywordRouter() ;
    }

    public function addRoute($handler)
    {
        $this->route[] = $handler;
    }

    public function getRoute()
    {
        return $this->route ;
    }

    public function setKeyword($keyword)
    {
        $this->keyword = $keyword ;
    }
    public function getKeyword()
    {
        return $this->keyword ;
    }

    /**
     * Return the Keyword Index after searching through collated keyword
     * @param $content
     */
    public function processIndexFromRequest($content)
    {

    }

    public function getRouter()
    {
        return $this->router ;
    }

    public function collateRouteAsRouter()
    {
        $all_route = $this->getRoute() ;
        ##
        if(! is_array($all_route))    {
            throw new KeywordRouteException(sprintf('No keyword route class detect or tag yet, please create Keyword route and tag them as %s' , KeywordRouteAggregator::SERVICE_TAG_ALIAS ));
        }


        foreach ($this->getRoute() as $routeCollection)     {
            ##
            if($routeCollection instanceof KeywordRouteInterface)    {
                ##
                $routeCollection = $routeCollection->getRouter() ;
                ##
            }

            foreach ($routeCollection as $routeTemplate => $routeHandler)    {
                ##
                if(is_array($routeHandler))    {
                    ##
                    $this->createRouter($routeTemplate , $routeHandler[0] , $routeHandler[1]);
                }else{
                    ##
                    $this->createRouter($routeTemplate , $routeHandler);
                }
            }
        }

    }
    public function createRouter($route , $target = [], $name = null)
    {
        $this->getRouter()->map($route , $target , $name);
    }

    public function match($keyword=null)
    {
        if(null != $keyword)    {
           $this->setKeyword($keyword);
        }

       $this->routeMatch = $this->getRouter()->match($this->getKeyword() ) ;
       ##
       return $this->routeMatch;
    }

    public function getRouteMatch()
    {
        return $this->routeMatch ;
    }



}