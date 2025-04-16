<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PokemonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:100',
            'stats' => 'required|array',
            'trainer_id' => 'nullable|exists:trainers,id',
        ];

        // If updating, make most fields optional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['name'] = 'sometimes|string|max:255';
            $rules['type'] = 'sometimes|string|max:255';
            $rules['stats'] = 'sometimes|array';
        }

        return $rules;
    }
}