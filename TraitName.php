<?php
declare(strict_types=1);

namespace Veiw\BotLogic;


trait TraitName
{
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