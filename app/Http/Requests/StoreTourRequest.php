<?php

namespace App\Http\Requests;

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
        return false;
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
            'price' => 'numeric|min:0|digits_between:3,8'
        ];
    }
}
