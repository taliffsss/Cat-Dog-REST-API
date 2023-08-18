<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Api\Contracts\CatServiceInterface;
use App\Services\Api\Contracts\DogServiceInterface;
use App\Services\Api\CatApiService;
use App\Services\Api\DogApiService;
use GuzzleHttp\Client;

class AnimalServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CatServiceInterface::class, function($app) {
            return new CatApiService(new Client([
                'base_uri' => env('CAT_API'),
            ]), env('ANIMAL_KEY'));
        });

        $this->app->bind(DogServiceInterface::class, function($app) {
            return new DogApiService(new Client([
                'base_uri' => env('DOG_API'),
            ]), env('ANIMAL_KEY'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
