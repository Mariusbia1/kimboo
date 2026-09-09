<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'professeur';
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'phone'               => ['required', 'string', 'max:25'],
            'ville'               => ['required', 'string', 'max:100'],
            'experience_years'    => ['required', 'string', 'max:50'],
            'bio'                 => ['required', 'string', 'min:10', 'max:3000'],
            'a_propos_cours'      => ['required', 'string', 'min:10', 'max:3000'],
            'response_time'       => ['required', 'integer', 'min:1'],
            'zone_deplacement'    => ['nullable', 'string', 'max:150'],
            'zone_distance'       => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'zone_unit'           => ['nullable', 'string', 'in:km,m,ville,aucun'],
            'zone_precisions'     => ['nullable', 'string', 'max:150'],
            'video_url'           => ['nullable', 'url', 'max:255'],
            'avatar'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'parcours_academique' => ['nullable', 'array'],
            'parcours_academique.*.annees'        => ['nullable', 'string', 'max:50'],
            'parcours_academique.*.diplome'       => ['nullable', 'string', 'max:150'],
            'parcours_academique.*.etablissement' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'Votre nom complet est obligatoire.',
            'phone.required'           => 'Votre numéro de téléphone est obligatoire.',
            'ville.required'           => 'Votre ville de résidence est obligatoire.',
            'experience_years.required'=> 'Vos années d\'expérience sont obligatoires.',
            'bio.required'             => 'La section « À propos de vous » (présentation, parcours) est obligatoire.',
            'bio.min'                  => 'La section « À propos de vous » doit contenir au moins 10 caractères.',
            'a_propos_cours.required'  => 'La section « À propos du cours » (méthodologie, approche) est obligatoire.',
            'a_propos_cours.min'       => 'La section « À propos du cours » doit contenir au moins 10 caractères.',
            'response_time.required'   => 'Le temps de réponse moyen est obligatoire.',
        ];
    }
}