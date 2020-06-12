<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Core\ServiceProvider;


use Symfony\Component\HttpKernel\Event\ResponseEvent;

interface ServiceProviderResponseInterface
{
    public function execute(ResponseEvent $response) ;
    public function getChannelName() ;
    public function getServiceProviderName() ;
}