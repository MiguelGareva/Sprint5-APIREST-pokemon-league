<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimulateBattleRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trainer1_id' => 'required|integer|exists:trainers,id',
            'trainer2_id' => 'required|integer|exists:trainers,id|different:trainer1_id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'trainer1_id.required' => 'The first trainer is required.',
            'trainer1_id.exists' => 'The selected first trainer is invalid.',
            'trainer2_id.required' => 'The second trainer is required.',
            'trainer2_id.exists' => 'The selected second trainer is invalid.',
            'trainer2_id.different' => 'The trainers must be different.',
        ];
    }
}