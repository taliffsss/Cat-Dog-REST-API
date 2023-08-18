<?php

namespace App\Http\Controllers;

use App\Services\Api\Contracts\CatServiceInterface;
use App\Services\Api\Contracts\DogServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

/**
 * Animal Controller handles operations related to Animal data, including fetching breeds and images.
 */
class AnimalController extends Controller
{
    /**
     * Instantiate a new AnimalController instance.
     * 
     * @param CatServiceInterface $cat
     * @param DogServiceInterface $dog
     */
    public function __construct(protected CatServiceInterface $cat, protected DogServiceInterface $dog)
    {
        $this->cat = $cat;
        $this->dog = $dog;
    }

    /**
     * Makes request to fetch data using provided endpoint and parameters.
     *
     * @param string $endpoint
     * @param mixed $params
     * @return array
     */
    private function makeRequest(string $endpoint, mixed $params): array
    {
        // Fetch cat data
        $cats = $this->cat->fetch($endpoint, $params) ?? [];
        // Fetch dog data
        $dogs = $this->dog->fetch($endpoint, $params) ?? [];

        // Merge non-empty cat and dog results
        return array_merge(...array_filter([$cats, $dogs]));
    }

    /**
     * Retrieves a list of breeds.
     *
     * @param Request $request
     * @return Response
     */
    public function getBreeds(Request $request): Response
    {
        // Validate the provided request parameters
        $validator = Validator::make($request->all(), [
            'limit' => 'integer',
            'page' => 'integer',
        ]);

        // Return errors if validation fails
        if ($validator->fails()) {
            return $this->error($validator->errors()->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Get the provided limit and page parameters or default values
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 0);

        // Make the request for breed data
        $data = $this->makeRequest('breeds', ['limit' => $limit, 'page' => $page]);

        // Return the data in a success response
        return $this->success('Fetch Successfully', $data, Response::HTTP_OK);
    }

    /**
     * Retrieves a specific breed by its ID.
     *
     * @param Request $request
     * @param mixed $breedId
     * @return Response
     */
    public function getBreed(Request $request, mixed $breedId): Response
    {
        // Validate the provided breed ID and pagination parameters
        $validator = Validator::make(['id' => $breedId, 'page' => $request->input('page'), 'limit' => $request->input('limit')], [
            'id' => 'required',
            'page' => 'nullable|integer',
            'limit' => 'nullable|integer',
        ]);

        // Return errors if validation fails
        if ($validator->fails()) {
            return $this->error($validator->errors()->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Compute cache key for storing breed data
        $cacheKey = md5('breeds' . serialize($validator->validated()));

        // Get data from cache or fetch if not present in cache
        $data = Cache::get($cacheKey) ?? $this->makeRequest('breeds', ['breed_id' => $breedId]);

        // Store data in cache for 1 minute if not already cached
        Cache::remember($cacheKey, 60, fn() => $data);

        // Paginate the data
        $perPage = $request->input('limit', 10);
        $currentPage = $request->input('page', 0);
        $offset = ($currentPage - 1) * $perPage;
        $data = array_slice($data, $offset, $perPage);

        // Return the paginated data in a success response
        return $this->success('Fetch Successfully', $data, Response::HTTP_OK);
    }

    /**
     * Retrieves an image by its ID.
     *
     * @param Request $request
     * @param mixed $id
     * @return Response
     */
    public function getImage(Request $request, mixed $id): Response
    {
        // Validate the provided image ID
        $validator = Validator::make(['id' => $id], [
            'id' => 'required',
        ]);

        // Return errors if validation fails
        if ($validator->fails()) {
            return $this->error($validator->errors()->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Make the request for image data
        $data = $this->makeRequest('images', ['image_id' => $id]);

        // Return the data in a success response
        return $this->success('Fetch Successfully', $data, Response::HTTP_OK);
    }
}
