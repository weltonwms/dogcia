<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProductAvailable implements Rule
{
   private $entidade = null; //representa ou um rent ou uma venda; 
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($entidade)
    {
        $this->entidade = $entidade;
       
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $request = collect(json_decode($value));
        $produtos = \DB::table('produtos')->whereIn('id', $request->pluck('produto_id'))->get();
        if ($this->entidade) {
           $pivotQtd = $this->entidade->produtos->pluck('pivot.qtd','id');
        }

        $retorno = true;

        $reqQtd = $request->pluck('qtd', 'produto_id');
        foreach ($produtos as $produto):
            $newQtdDisponivel = $produto->qtd_disponivel - $reqQtd[$produto->id];
            if ($this->entidade && isset($pivotQtd[$produto->id]) ):
                $newQtdDisponivel += $pivotQtd[$produto->id];
            endif;
            if ($newQtdDisponivel < 0):
                $retorno = false;
                break;
            endif;

        endforeach;
        return $retorno;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Produto(s) com Qtd Maior que Qtd Disponível.';
    }
}
