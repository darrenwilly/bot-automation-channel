<?php
declare(strict_types=1);

namespace Veiw\BotLogic\Core\Keyword;

interface KeywordChannelHandlerInterface
{
    public function execute() ;
    public function getChannel() ;
    public function getKeyword() ;
    public function route() ;
    public function help() ;
}