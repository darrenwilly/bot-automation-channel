<?php

/**
 * add the list of all keywords here
 */
$config = [
    'keyword-aggregator' => [
        \Veiw\BotLogic\KeywordStackLogic\Job::configureChannel() ,
        \Veiw\BotLogic\KeywordStackLogic\Em::configureChannel() ,
        \Veiw\BotLogic\KeywordStackLogic\Ad::configureChannel() ,
    ]
] ;

return $config ;