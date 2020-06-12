<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Core\Channel;

abstract class BaseAbstractAdapter implements ChannelAdapterInterface
{
    use TraitResult;

    protected $name ;

    public function getName()
    {
        return $this->name ;
    }
    public function setName($name)
    {
        $this->name = $name ;
    }

}