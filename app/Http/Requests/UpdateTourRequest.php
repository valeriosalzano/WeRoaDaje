<?php

namespace App\Http\Requests;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'travelId' => 'exists:travels,id',
            'name' => 'required|max:14',
            'startingDate' => 'required|date|after:today',
            'endingDate' => 'required|date|after:startingDate',
            'price' => 'required|numeric|min:0|digits_between:3,8'
        ];
    }
}
