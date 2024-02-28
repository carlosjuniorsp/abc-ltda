<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesFormRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'amount' => 'required',
                        'product' => 'required',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [];
                }
            default:
                break;
        }
    }

    public function messages()
    {
        return [
            'required' => "O Campo :attribute é obrigatório",
        ];
    }
}
