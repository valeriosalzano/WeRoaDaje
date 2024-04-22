<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Mood;
use App\Models\Travel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\Throw_;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TravelController extends Controller
{
    use ApiHelpers;
    use SoftDeletes;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Travel::query()->with('mood');
        if ($request->user()->tokenCan('admin')) {
            $travels = $query->withTrashed()->get();
            return $this->onSuccess($travels, 'Travels retrieved', 200);
        } else {
            $travels = $query->get();
            return $this->onSuccess(TravelResource::collection($travels), 'Travels retrieved', 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreTravelRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTravelRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $newTravel = Travel::create($validated);

            if ($request->mood)
                $newTravel->mood()->create($request->mood);

            $newTravel->load('mood');

            return $this->onSuccess($newTravel, 'Travel Created');
        } catch (ValidationException $err) {
            $errors = $err->validator->errors()->all();
            return $this->onError(422, 'Travel Creation Failed', $errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function show($slug): JsonResponse
    {
        try {
            $travel = Travel::with('mood')->where('slug', $slug)->firstOrFail();

            return $this->onSuccess($travel, 'Travel found.', 200);
        } catch (ModelNotFoundException $ex) {
            return $this->onError(404, 'Travel not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateTravelRequest  $request
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTravelRequest $request, Travel $travel): JsonResponse
    {
        try {
            $validated = $request->validated();
            $travel->update($validated);

            if ($request->mood) {
                $travel->load('mood');

                if (!$travel->mood) {
                    $mood = Mood::create($request->mood);
                    $travel->mood->save($mood);
                } else {
                    $travel->mood->update($request->mood);
                }
            }
            return $this->onSuccess($travel, 'Travel Updated');
        } catch (ValidationException $err) {
            $errors = $err->validator->errors()->all();
            return $this->onError(422, 'Travel Update Failed', $errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($slug)
    {
        try {
            $travel = Travel::with('mood')->where('slug', $slug)->firstOrFail();

            $travel->delete();
            return $this->onSuccess($travel, 'Travel deleted.', 204);
        } catch (ModelNotFoundException $ex) {
            return $this->onError(404, 'Travel not found.');
        }
    }
}
