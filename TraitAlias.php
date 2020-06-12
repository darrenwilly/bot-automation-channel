<?php
declare(strict_types=1);

namespace Veiw\BotLogic;


trait TraitAlias
{
    protected $alias = [] ;

    public function getAlias()
    {
        return $this->alias ;
    }
    public function setAlias($alias)
    {
        $this->alias = $alias ;
    }
}