<?php
declare(strict_types=1) ;

namespace Veiw\BotLogic\Service;


trait TraitFlattenArray
{

    public function flatten($array)
    {   ##
        if (!is_array($array)) {
            // nothing to do if it's not an array
            return [$array];
        }

        $result = [];
        foreach ($array as $value) {
            // explode the sub-array, and add the parts
            $result = array_merge($result, $this->flatten($value));
        }
        ##
        return $result;
    }
}