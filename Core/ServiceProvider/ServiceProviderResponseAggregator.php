<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\ServiceProvider;

class ServiceProviderResponseAggregator
{
    const SERVICE_TAG_ALIAS = 'bac.service_provider_response' ;

    protected $response = [];

    public function __construct()
    {
        $this->response ;
    }

    public function collateResponse(ServiceProviderResponseInterface $response)
    {
        $this->response[$response->getChannelName()][$response->getServiceProviderName()] = $response;
    }

    public function getResponse($channel=null , $serviceProvider=null)
    {
        if (isset($this->response[$channel]) )    {
            ## grab the adapter
            $adapterOptionsCollection = $this->response[$channel] ;
            ##
            if(null != $serviceProvider && isset($adapterOptionsCollection[$serviceProvider]) )    {
                ##
                return $adapterOptionsCollection[$serviceProvider] ;
            }
            ##
            return $adapterOptionsCollection ;
        }
    }

    public function hasResponse($channel , $serviceProvider=null)
    {
        if(null != $serviceProvider)    {
            ##
            return isset($this->response[$channel][$serviceProvider]) ;
        }
        ##
        return isset($this->response[$channel]) ;
    }


}