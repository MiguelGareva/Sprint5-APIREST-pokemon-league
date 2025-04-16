<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BattleRequest extends FormRequest
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
        return [
            'trainer1_id' => 'required|exists:trainers,id',
            'trainer2_id' => 'required|exists:trainers,id|different:trainer1_id',
            'date' => 'nullable|date',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'trainer2_id.different' => 'A trainer cannot battle against themselves',
        ];
    }
}