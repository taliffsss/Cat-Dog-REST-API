<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalBreedRequest;
use App\Http\Requests\AnimalImageRequest;
use App\Services\Api\Contracts\CatServiceInterface;
use App\Services\Api\Contracts\DogServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class AnimalController extends Controller
{
    public function __construct(private CatServiceInterface $cat, private DogServiceInterface $dog) {}



    public function getDogImage($dogs)
    {
        if (!empty($dogs)) {
            return collect($dogs)->map(function ($item) {
                $item['url'] = $this->dog->fetch('images/' . $item['reference_image_id'])['url'];
                $item['isDog'] = true;
                return $item;
            })->toArray();
        }

        return $dogs;
    }

    public function getBreeds(AnimalBreedRequest $request): Response
    {
        $perPage = $request->input('limit', 10);
        $currentPage = $request->input('page', 1);
        
        $cacheKey = 'breeds:' . md5(http_build_query($request->query()));
        $data = Cache::remember($cacheKey, 60, fn() => $this->fetchBreeds($request));

        return $this->success('Fetch Successfully', $data, Response::HTTP_OK, $perPage, $currentPage);
    }

    public function getBreed(AnimalBreedRequest $request, mixed $breedId): Response
    {
        $perPage = $request->input('limit', 10);
        $currentPage = $request->input('page', 1);

        $cacheKey = "breed:{$breedId}:" . md5(http_build_query($request->query()));
        $data = Cache::remember($cacheKey, 60, fn() => $this->fetchBreed($breedId, $request));

        return $this->success('Fetch Successfully', $data, Response::HTTP_OK);
    }

    public function getImageOfDog(AnimalImageRequest $request, mixed $id): Response
    {
        $data = $this->dog->fetch('images/' . $id) ?? [];
        return $this->success('Fetch Successfully', $data, Response::HTTP_OK);
    }

    public function getImageOfCat(AnimalImageRequest $request, mixed $id): Response
    {
        $data = $this->cat->fetch('images/' . $id) ?? [];
        return $this->success('Fetch Successfully', $data, Response::HTTP_OK);
    }

    private function fetchBreeds(AnimalBreedRequest $request): array
    {
        $perPage = $request->input('limit', 10);
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $params = [
            'limit' => $perPage,
            'page' => $offset
        ];

        $cats = $this->cat->fetch('breeds', $params) ?? [];
        $dogs = $this->dog->fetch('breeds', $params) ?? [];
        $dogs = $this->getDogImage($dogs);

        $breeds = array_merge(...array_filter([$dogs, $cats]));

        return collect($breeds)->map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'alt' => $item['alt_names'] ?? $item['name'],
                'description' => $item['description'] ?? $item['temperament'],
                'url' => $item['image']['url'] ?? ($item['url'] ?? 'https://via.placeholder.com/300'),
                'height' => $item['height']['metric'] ?? null,
                'weight' => $item['weight']['metric'] ?? null,
                'life_span' => $item['life_span'] ?? null,
                'breed_group' => $item['breed_group'] ?? null,
                'bred_for' => $item['bred_for'] ?? null,
                'origin' => $item['origin'] ?? null,
                'child_friendly' => $item['child_friendly'] ?? 0,
                'adaptability' => $item['adaptability'] ?? 0,
                'dog_friendly' => $item['dog_friendly'] ?? 0,
                'intelligence' => $item['intelligence'] ?? 0,
                'temperament' => $item['temperament'] ?? null,
                'isDog' => $item['isDog'] ?? false,
            ];
        })->toArray();
    }

    private function fetchBreed($breedId, AnimalBreedRequest $request): array
    {
        $perPage = $request->input('limit', 10);
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        if (ctype_digit($breedId)) {
            $data = $this->dog->fetch('breeds/' . $breedId) ?? [];
        } else {
            $data = $this->cat->fetch('breeds/' . $breedId) ?? [];
        }

        $data = array_slice($data, $offset, $perPage);

        return $data;
    }
}
