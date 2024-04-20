<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UpdateTravelRequest extends FormRequest
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
            'name' => 'max:100',
            'slug' => ['max:100',Rule::unique('travels','slug')->ignore($this->slug,'slug')],
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'numberOfDays' => 'numeric|min:1|digits_between:1,2',
            'moods' => 'array:nature,relax,history,culture,party'
        ];
    }
}
