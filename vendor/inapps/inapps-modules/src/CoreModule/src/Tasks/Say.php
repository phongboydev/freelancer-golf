<?php

namespace Modules\IACoreModule\Tasks;

class Say
{
    /**
     * @param $name
     * @return string
     */
    public function hello($name)
    {
        $str = 'Hello ' . $name;
        echo "<pre>";
        echo "I am hello task. I am loaded in Task Say";
        echo "</pre>";
        return $str;
    }
}