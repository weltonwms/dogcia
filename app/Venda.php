<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon ;
use Collective\Html\Eloquent\FormAccessible;

class Venda extends Model
{
    use FormAccessible;
    protected $fillable=['cliente_id','data_venda','observacao'];
    protected $dates = array('data_venda');
    
    
    public function produtos()
    {
        return $this->belongsToMany('App\Produto')
                ->using('App\ProdutoVenda')
                ->withPivot('qtd', 'valor_venda')
                ->withTimestamps();
    }
    
    public function cliente(){
        return $this->belongsTo("App\Cliente");
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
             $x->valor_venda=$produto->pivot->valor_venda;
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

    public function getTotalGeralFormatado(){
        return "R$ ".number_format($this->getTotalGeral(),2,",",".");
    }
}
