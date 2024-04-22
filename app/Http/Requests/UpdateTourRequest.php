<?php

namespace App\Http\Requests;

use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTourRequest extends FormRequest
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
        if($this->slug && !$this->travelId){
            $travel = Travel::where('slug',$this->slug)->first();
            $this->merge([
                'travelId' => $travel->id
            ]);
        } 
     }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'slug' => 'string|exists:travels,slug',
            'travelId' => 'required|exists:travels,id',
            'name' => 'required|max:14',
            'startingDate' => 'required|date|after:today',
            'endingDate' => 'required|date|after:startingDate',
            'price' => 'required|numeric|min:0|digits_between:3,8'
        ];
    }
}
