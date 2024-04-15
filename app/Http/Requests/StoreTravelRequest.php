<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreTravelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ;
    }

    // Generate slug before validation
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->slug ?? Str::slug($this->name, '-'),
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
            'visible' => 'boolean',
            'name' => 'required|max:100',
            'slug' => 'required|max:100|unique:travels',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'numberOfDays' => 'numeric|min:1|digits_between:1,2'
        ];
    }
}
