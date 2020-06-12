<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\ServiceProvider;

class ServiceProviderAggregator
{
    const SERVICE_TAG_ALIAS = 'bac.service_provider.extension' ;

    private $extension;

    public function __construct()
    {
        $this->extension = [];
    }

    public function addExtension(ServiceProviderExtensionInterface $extension)
    {
        $this->extension[$extension->getName()] = $extension;
    }

    public function get($alias)
    {
        return $this->getExtension($alias) ;
    }
    public function getExtension($alias)
    {
        if (array_key_exists($alias , $this->extension)) {
            return $this->extension[$alias];
        }
    }

    public function hasExtension($alias)
    {
        return isset($this->extension[$alias]) ;
    }
    public function has($alias)
    {
        return $this->hasExtension($alias);
    }

}