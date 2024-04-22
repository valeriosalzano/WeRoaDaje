<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreTourRequest;
use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;

class TourController extends Controller
{

    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        $query = Tour::query()->with('travel');

        if ($request->route('slug')) {

            try {
                $travel = Travel::where('slug', $request->route('slug'))->where('isPublic',true)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return $this->onError(404, 'Travel not found');
            }

            $query->byTravelId($travel->id);
        }

        if ($request->filled('dateFrom')) {
            $query->dateFrom($request->query('dateFrom'));
        }

        if ($request->filled('dateTo')) {
            $query->dateTo($request->query('dateTo'));
        }

        if ($request->filled('priceFrom') || $request->filled('priceTo')) {
            $query->priceBetween($request->query('priceFrom') ?? 0, $request->query('priceTo') ?? 100000000);
        }

        if ($request->filled('orderByPrice')) {
            $query->orderByPrice($request->query('orderByPrice'));
        }

        $tours = $query->orderBy('startingDate', 'asc')->paginate(4);

        $tours->data = TourResource::collection($tours);

        return $this->onSuccess($tours, 'Tours retrieved', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreTourRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTourRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $newTour = Tour::create($validated);

            return $this->onSuccess($newTour, 'Tour Created', 201);
        } catch (ValidationException $err) {
            $errors = $err->validator->errors()->all();
            return $this->onError(422, 'Tour Creation Failed', $errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        try {
            if ($name == 'last') {
                $tour = Tour::orderBy('created_by', 'desc')->first();
            }

            $tour = Tour::where('name', $name)->firstOrFail();

            return $this->onSuccess($tour, 'Tour found.', 200);
        } catch (ModelNotFoundException $ex) {
            return $this->onError(404, 'Tour not found.');
        }
    }
}
