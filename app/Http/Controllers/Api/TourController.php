<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreTourRequest;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    public function index(Request $request):JsonResponse
    {
       
        $query = Tour::query();

        if($request->route('travel')){
            try {
                Travel::where('slug',$request->route('travel'))->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return $this->onError(404,'Travel not found');
            }
            $query->byTravelSlug($request->route('travel'));
        }

        if($request->filled('dateFrom')){
            $query->dateFrom($request->query('dateFrom'));
        }

        if($request->filled('dateTo')){
            $query->dateFrom($request->query('dateTo'));
        }

        if($request->filled('priceFrom') || $request->filled('priceTo')){
            $query->priceBetween($request->query('priceFrom') ?? 0, $request->query('priceTo') ?? 100000000);
        }

        if($request->filled('orderByPrice')){
            $query->orderByPrice($request->query('orderByPrice'));
        }

        $tours = $query->orderBy('startingDate','asc')->paginate(3);

        return response()->json([
            'status' => true,
            'tours' => $tours
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreTourRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTourRequest $request): JsonResponse
    {
        try
        {
            $validated = $request->validated();
            $newTour = Tour::create($validated);

            return $this->onSuccess($newTour,'Tour Created');

        } catch (ValidationException $err)
        {
            $errors = $err->validator->errors()->all();
            return $this->onError(422,'Tour Creation Failed',$errors);
        }
    }
}
