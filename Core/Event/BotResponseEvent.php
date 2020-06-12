<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Event ;

use Veiw\BotLogic\Presentation\Service\LogicResult;

final class BotResponseEvent extends BotEvent
{

    public function __construct(LogicResult $logicResult)
    {

    }
}
