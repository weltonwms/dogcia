<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MorteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'produto_id'=>"required",
            'data_morte'=>"required",
            'qtd'=>"required|numeric|min:1",
            'custo_medio'=>"required",
            
        ];
    }

    public function messages()
    {
        return[
            'produto_id.required' => 'O campo Produto é obrigatório.',
           
        ];
    }
}
