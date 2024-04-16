<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Models\Mood;
use App\Models\Travel;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreTravelRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTravelRequest $request): JsonResponse
    {
        try
        {
            $validated = $request->validated();
            $newTravel = Travel::create($validated);

            if($request->moods){
                $moods = Mood::create($request->moods);
                $newTravel->moods->save($moods);
                // $newTravel->load('moods');
            }

            return $this->onSuccess($newTravel,'Travel Created');

        } catch (ValidationException $err)
        {
            $errors = $err->validator->errors()->all();
            return $this->onError(422,'Travel Creation Failed',$errors);
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
        try
        {

            $validated = $request->validated();
            $travel->update($validated);

            if($request->moods){
                $travel->load('moods');
                
                if(!$travel->moods){
                    $moods = Mood::create($request->moods);
                    $travel->moods->save($moods);
                }else{
                    $travel->moods->update($request->moods);
                }
            }
            return $this->onSuccess($travel,'Travel Updated');

        } catch (ValidationException $err)
        {
            $errors = $err->validator->errors()->all();
            return $this->onError(422,'Travel Update Failed',$errors);
        }
    }
}
