<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreTourRequest;
use App\Models\Tour;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TourController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index():JsonResponse
    {
        $products = Tour::all();
        return response()->json([
            'status' => true,
            'products' => $$tours
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if(!$this->isAdmin($request->user())) return $this->onError(401, 'Unauthorized Access');

        $validated_data = $request->validated();
        $newTour = Tour::create($validated_data);

        return $this->onSuccess($newTour, 'Tour Created');
    }

    public function test(Request $request): JsonResponse
    {

        if ($this->isAdmin($request->user())) {
            $user = $request->user();
            return $this->onSuccess($user, 'User Retrieved');
        }

        return $this->onError(401, 'Unauthorized Access');
    }

}
