<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'professeur';
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['required', 'string'],
            'level' => ['required', 'string'],
            'format' => ['required', 'string'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'is_group' => ['boolean'],
            'max_students' => ['nullable', 'integer', 'min:2', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'category.required' => 'La catégorie est obligatoire.',
            'level.required' => 'Le niveau est obligatoire.',
            'format.required' => 'Le format est obligatoire.',
            'price_per_hour.required' => 'Le prix est obligatoire.',
        ];
    }
}
