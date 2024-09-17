<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeveloperRequest extends FormRequest
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
            'nome' => 'required|string|max:100',
            'sexo' => 'required|in:M,F',
            'data_nascimento' => 'required|date|before:today',
            'hobby' => 'nullable|string|max:100',
            'level_id' => 'required|exists:levels,id',
        ];
    }
    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'sexo.required' => 'O campo sexo é obrigatório.',
            'sexo.in' => 'O campo sexo deve ser M ou F.',
            'data_nascimento.required' => 'O campo data de nascimento é obrigatório.',
            'hobby.required' => 'O campo hobby é obrigatório.',
            'level_id.required' => 'O campo nível é obrigatório.',
            'level_id.exists' => 'O nível selecionado não existe.',
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'status' => 'Erro ao tentar cadastrar o desenvolvedor. Por favor, tente novamente.',
            'errors' => $validator->errors(),
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
