<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['professeur', 'admin']);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['required', 'string'],
            'new_category' => ['required_if:category,__new__', 'nullable', 'string', 'max:100'],
            'level' => ['required', 'string'],
            'format' => ['required', 'string'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'first_course_free' => ['nullable', 'boolean'],
            'is_group' => ['nullable', 'boolean'],
            'max_students' => ['nullable', 'integer', 'min:2', 'max:50'],
            'lieu_cours' => ['nullable', 'array'],
            'lieu_cours.*' => ['in:chez_prof,chez_eleve,webcam'],
            'zone_deplacement' => ['nullable', 'string', 'max:150'],
            'zone_distance'    => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'zone_unit'        => ['nullable', 'string', 'in:km,m,ville,aucun'],
            'zone_precisions'  => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'category.required' => 'La catégorie est obligatoire.',
            'new_category.required_if' => 'Veuillez saisir le nom de la nouvelle matière.',
            'level.required' => 'Le niveau est obligatoire.',
            'format.required' => 'Le format est obligatoire.',
            'price_per_hour.required' => 'Le prix est obligatoire.',
        ];
    }
}
