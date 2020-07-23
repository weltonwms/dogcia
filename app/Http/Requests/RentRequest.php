<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProductAvailable; //Validação no Servidor de Qtd Disponível

class RentRequest extends FormRequest
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
        $rent=$this->route('rent');
        
        return [
           'cliente_id'=>"required",
            'data_saida'=>"required|date",
            'data_retorno'=>"required|date",
            'produtos_json'=>['required','not_in:[]', new ProductAvailable($rent)]
        ];
    }
    
    public function messages()
    {
        return[
            'cliente_id.required' => 'O campo cliente é obrigatório.',
            'produtos_json.not_in' =>'Nenhum Produto Adicionado.',
            'produtos_json.required' =>'Nenhum Produto Adicionado.'
        ];
    }
}
