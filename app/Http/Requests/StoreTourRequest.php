<?php

namespace App\Http\Requests;

use App\Models\Tour;
use Illuminate\Foundation\Http\FormRequest;

class StoreTourRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // Generate name before validation
    protected function prepareForValidation(): void
    {
        $newName = $this->name ?? 'ITISO'.str_replace('-','',$this->startingDate);

        $duplicate = Tour::where('name',$newName)->exists();
        if($duplicate && strlen($newName) == 13)
        {
            $newName += 'A';
        }else if($duplicate && strlen($newName) == 14){
            $lastChar = substr($newName, -1);
            $newName[13] = chr((ord($lastChar))+1);
        }

        $this->merge([
            'name' => $newName
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'travelId' => 'required|exists:travels,id',
            'name' => 'required|max:14|uppercase|unique:tours',
            'startingDate' => 'required|date|after:today',
            'endingDate' => 'required|date|after:startingDate',
            'price' => 'required|numeric|min:0|digits_between:3,8'
        ];
    }
}
