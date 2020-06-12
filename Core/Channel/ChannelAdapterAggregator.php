<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Channel;

class ChannelAdapterAggregator
{
    const SERVICE_TAG_ALIAS = 'bac.channel_adapter.options' ;

    private $adapterOptions;

    public function __construct()
    {
        $this->adapterOptions = [];
    }

    public function addChannelOptions($adapterOptions)
    {
        $this->adapterOptions[$adapterOptions->getChannelName()][$adapterOptions->getServiceProviderName()] = $adapterOptions;
    }

    public function getChannelOptions($channel , $serviceProvider=null)
    {
        if (isset($this->adapterOptions[$channel]) )    {
            ## grab the adapter
            $adapterOptionsCollection = $this->adapterOptions[$channel] ;
            ##
            if(null != $serviceProvider && isset($adapterOptionsCollection[$serviceProvider]) )    {
                ##
                return $adapterOptionsCollection[$serviceProvider] ;
            }
            ##
            return $adapterOptionsCollection ;
        }
    }

    public function hasChannelOptions($channel , $serviceProvider=null)
    {
        if(null != $serviceProvider)    {
            ##
            return isset($this->adapterOptions[$channel][$serviceProvider]) ;
        }
        ##
        return isset($this->adapterOptions[$channel]) ;
    }


}