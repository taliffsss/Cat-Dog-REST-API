<?php
namespace App\Services\Api;

use App\Services\Api\Contracts\DogServiceInterface;
use GuzzleHttp\Client;

class DogApiService implements DogServiceInterface {

    public function __construct(protected Client $client, protected string $apiKey) {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function fetch(string $endpoint, ?array $params = []): array
    {
        $options = [
            'headers' => [
                'x-api-key' => $this->apiKey,
            ],
            'query' => $params 
        ];
        $response = $this->client->request('GET', $endpoint, $options);
        
        return json_decode($response->getBody(), true);
    }
}
