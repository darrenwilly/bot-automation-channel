<?php

if(! defined('BAC_BUNDLE_DIR'))    {
    ##
    define('BAC_BUNDLE_DIR' , __DIR__) ;
}

if(! defined('CHANNEL_SMS_SHORTCODE'))    {
    define('CHANNEL_SMS_SHORTCODE' , 'shortcode') ;
}

if(! defined('CHANNEL_USSD'))    {
    define('CHANNEL_USSD' , 'ussd') ;
}

if(! defined('CHANNEL_FACEBOOK_MESSENGER'))    {
    define('CHANNEL_FACEBOOK_MESSENGER' , 'fbm') ;
}

if(! defined('CHANNEL_WHATSAPP'))    {
    define('CHANNEL_WHATSAPP' , 'whatsapp') ;
}

if(! defined('CHANNEL_TELEGRAM'))    {
    define('CHANNEL_TELEGRAM' , 'telegram') ;
}

if(! defined('CHANNEL_EMAIL'))    {
    define('CHANNEL_EMAIL' , 'email') ;
}