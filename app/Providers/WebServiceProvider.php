<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\WebService\WebService;
class WebServiceProvider extends ServiceProvider {

    public function register()
    {

        $this->app->bind('WebService', function () {
            return new WebService;
        });
    }

}