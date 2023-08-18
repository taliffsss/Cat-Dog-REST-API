<?php

namespace Tests\Unit;

use App\Http\Controllers\AnimalController;
use App\Services\Api\Contracts\CatServiceInterface;
use App\Services\Api\Contracts\DogServiceInterface;
use Illuminate\Http\Request;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnimalControllerTest extends TestCase
{
    private $catService;
    private $dogService;
    private $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->catService = Mockery::mock(CatServiceInterface::class);
        $this->dogService = Mockery::mock(DogServiceInterface::class);

        $this->controller = new AnimalController($this->catService, $this->dogService);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetBreeds()
    {
        // make a GET request to the route (adjust the route as per your application's route)
        $response = $this->json('GET', '/api/v1/breeds', ['limit' => 5, 'page' => 1]);

        $response->assertStatus(200);
    }
}
