<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMoodRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "travelId" => 'unique|exists:travels,id',
            "nature"=> 'required|numeric|min:0|max:100',
            "relax"=> 'required|numeric|min:0|max:100',
            "history"=> 'required|numeric|min:0|max:100',
            "culture"=> 'required|numeric|min:0|max:100',
            "party"=> 'required|numeric|min:0|max:100'        
        ];
    }
}
