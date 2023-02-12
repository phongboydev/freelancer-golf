<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WebService extends Facade {

    protected static function getFacadeAccessor() {
        return 'WebService';
    }

}