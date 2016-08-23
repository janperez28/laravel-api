<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {        
        $this->app->resolving(ResponseFactory::class, function(ResponseFactory $response)
        {
            $this->addResponseMacro($response);
        });        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //        
    }
    
    /**
     * Add our API macro method for sending API (JSON encoded) response on the response factory.
     *
     * @param ResponseFactory $response
     * @return void
     */
    protected function addResponseMacro(ResponseFactory $response)
    {        
        // @param boolean $success
        // @param optional mixed $data 
        // @return 
        $response::macro('api', function($success, $data = null) use ($response)
        {                    
            $payload = compact('success');
            
            // $data must be an array error messsages if not successful
            if ($data)
            {
                $payload[$success ? 'data' : 'errors'] = $data;
            }
            
            return $response->json($payload);
        });
    }
}
