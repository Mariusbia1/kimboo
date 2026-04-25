<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'professeur';
    }

    public function rules(): array
    {
        return [
            'bio' => ['required', 'string', 'max:1000'],
            'a_propos_cours' => ['nullable', 'string', 'max:1000'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'experience_years' => ['required', 'string', 'max:50'],
            'first_course_free' => ['boolean'],
            'ville' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'lieu_cours' => ['nullable', 'array'],
            'lieu_cours.*' => ['in:chez_prof,chez_eleve,webcam'],
            'zone_deplacement' => ['nullable', 'string', 'max:100'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'parcours_academique' => ['nullable', 'array'],
            'parcours_academique.*.annees' => ['nullable', 'string'],
            'parcours_academique.*.diplome' => ['nullable', 'string'],
            'parcours_academique.*.etablissement' => ['nullable', 'string'],
            'response_time' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'bio.required' => 'La biographie est obligatoire.',
            'hourly_rate.required' => 'Le tarif horaire est obligatoire.',
            'experience_years.required' => 'L\'expérience est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
        ];
    }
}