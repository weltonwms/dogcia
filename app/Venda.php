<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon ;
use Collective\Html\Eloquent\FormAccessible;

class Venda extends Model
{
    use FormAccessible;


    public static function boot() 
    {
        //add listener via boot;
        parent::boot();
        self::deleting(function($venda){ 
            \Log::info('Deleting Venda '.$venda->id." acionado.");
            //removendo produtos da venda antes de remover a venda
            //Ação necessária para disparar eventos e problemas com banco se não houvesse cascade
            //Poderia usar detach, mas para disparar evento no detach tem que passar array ids.
            $venda->produtos()->sync([]); 
            return true; // let the delete go through
        });
    }

    protected $fillable=['cliente_id','data_venda','frete', 'status', 
    'carteira' ,'forma_pagamento', 'seller_id', 'observacao'];
    protected $dates = array('data_venda');
    
    
    public function produtos()
    {
        return $this->belongsToMany('App\Produto')
                ->using('App\ProdutoVenda')
                ->withPivot('qtd', 'valor_venda', 'custo_medio','granel')
                ->withTimestamps();
    }
    
    public function cliente(){
        return $this->belongsTo("App\Cliente");
    }

    public function seller(){
        return $this->belongsTo("App\Seller");
    }

    public static function getAllByFiltros(){
        $query = self::with('cliente')->with('seller');
        $request = request();
        if (is_numeric($request->frete)):
            $query->where('frete', $request->frete);
        endif;
        if (is_numeric($request->carteira)):
            $query->where('carteira', $request->carteira);
        endif;
        if (is_numeric($request->status)):
            $query->where('status',  $request->status);
        endif;
        return $query->get();
        
    }
    
     public function getDataVendaAttribute($value)
    {
       return Carbon::parse($value)->format('d/m/Y');
    }
    
     public function formDataVendaAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    
    public function setDataVendaAttribute($value){
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if($value){
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $this->attributes['data_venda'] = Carbon::parse($value)->format('Y-m-d');
        } 
        else{
            $this->attributes['data_venda'] = null;
        }    
       
    }
    

    public function getProdutosJsonAttribute() {
         $x= $this->produtos;
         $list=[];
         foreach($this->produtos as $produto):
             $x= new \stdClass();
             $x->produto_id=$produto->id;
             $x->qtd=$produto->pivot->qtd;
             $x->granel=$produto->pivot->granel;
             $x->valor_venda=$produto->pivot->valor_venda;
             $x->custo_medio=$produto->pivot->custo_medio;
             $list[]=$x;
         endforeach;
         return json_encode($list);
         //return '[{"produto_id":"5","qtd":"37","valor_venda":"10.00"},{"produto_id":"4","qtd":"30","valor_venda":"1.00"}]';
    }

    public function getTotalGeral(){
        $total=0;
        foreach($this->produtos as $produto):
            $total+=$produto->pivot->getTotal();
        endforeach;
        return $total;
    }

    public function isFreteNome(){
        return $this->frete?"Sim":"Não";
    }

    public function isCarteiraNome(){
        return $this->carteira?"Sim":"Não";
    }

    public function statusNome(){
        return $this->status==1?"Pago":"Não Pago";
    }

    public function formaPagamentoNome(){
        $nomes=["","Dinheiro","Cartão Crédito","Cartão Débito"];
        if(isset($nomes[$this->forma_pagamento])):
            return $nomes[$this->forma_pagamento];
        endif;
    }
    
}
