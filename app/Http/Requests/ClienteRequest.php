<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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

        $cliente=$this->route('cliente');
        $id=$cliente?$cliente->id:null;
        $ruleEmail= request('email')?"email|unique:clientes,email,$id":"";
        $ruleCpf= request('cpf')?"unique:clientes,cpf,$id":"";
         return [
            'nome'=>"required",
            'telefone'=>"required",
            'email'=>$ruleEmail,
            'cpf'=>$ruleCpf
            
        ];
    }
}
