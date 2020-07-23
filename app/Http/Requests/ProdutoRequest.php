<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
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
        $produto=$this->route('produto');
        $nrMin=0;
        if($produto):
            $nrMin=$produto->countRents();
        endif;
       
        return [
            'nome'=>"required",
            'valor_aluguel'=>"required",
            'valor_venda'=>"required",
            'qtd_estoque'=>"required|numeric|min:$nrMin"
        ];
    }
}
