<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Core\ServiceProvider;

interface ServiceProviderExtensionInterface
{
    public function getName() ;
    public function getSupportedChannel();
    public function hasChannel($alias);
    public function getChannel($alias);
    public function getRequestType();
}