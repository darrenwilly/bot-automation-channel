<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Core\ServiceProvider;

interface ServiceProviderChannelInterface
{
    public function getChannelName() ;
    public function getServiceProviderName() ;
    public function getToKeyIdentifier();
    public function getFromKeyIdentifier();
    public function getContentKeyIdentifier();
}