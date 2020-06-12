<?php
namespace Veiw\BotLogic\Core\EventSubscriber\BotCollateKeyword;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Veiw\BotLogic\Core\Event\BotCollateKeywordEvent;
use Veiw\BotLogic\Core\Event\BotEvent;
use Psr\Container\ContainerInterface;
use Veiw\BotLogic\Core\Keyword\RootStack\FBMRoot;
use Veiw\BotLogic\Core\Keyword\RootStack\RootInterface;
use Veiw\BotLogic\Core\Keyword\RootStack\ShortcodeRoot;
use Veiw\BotLogic\Core\Keyword\RootStack\TelegramRoot;
use Veiw\BotLogic\Core\Keyword\RootStack\UssdRoot;
use Veiw\BotLogic\Core\Keyword\RootStack\WhatsappRoot;


class InvalidKeywordSubscriber implements EventSubscriberInterface
{

    protected $container ;

    static public function getSubscribedEvents()
    {
        return [
            BotEvent::BOT_COLLATE_KEYWORD =>  [
                ['attachInvalidKeywordTarget' , -1000] ,
            ]
        ];
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container ;
    }

    public function attachInvalidKeywordTarget(BotCollateKeywordEvent $collateKeywordEvent , $eventName , EventDispatcherInterface $dispatcher)
    {
        $this->validateRequirement($collateKeywordEvent) ;

        /**
         * If the Target has not been set at this point, then choose a defualt responder based on the Channel Root
         */
        if(empty($collateKeywordEvent->getTarget()))    {
            ##
            $collateKeywordEvent->setTarget($this->chooseFromAdapterRoot($collateKeywordEvent->getChannel()->getChannelName())) ;
        }

    }

    protected function validateRequirement(BotCollateKeywordEvent $collateKeywordEvent)
    {
        if(null == strlen($collateKeywordEvent->getContent()))    {
            throw new \RuntimeException('Content from service provider must be set before keyword locator can work efficiently') ;
        }
    }

    public function chooseFromAdapterRoot($adapter)
    {
        switch (strtolower($adapter))   {
            case 'shortcode':
                return ShortcodeRoot::class ;
                break ;
            case 'ussd':
                return UssdRoot::class ;
                break ;
            case 'whatsapp' :
                return WhatsappRoot::class ;
                break;
            case 'telegram':
                return TelegramRoot::class ;
                break;
            case 'fbm':
            case 'facebook':
                return FBMRoot::class ;
                break ;
            default:
                return RootInterface::class ;
        }
    }

}