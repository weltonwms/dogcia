<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon ;
use Collective\Html\Eloquent\FormAccessible;

class Rent extends Model
{
    use FormAccessible;
    protected $fillable=['cliente_id','data_saida','data_retorno','observacao'];
    protected $dates = array('data_saida','data_retorno');
    
    
    public function produtos()
    {
        return $this->belongsToMany('App\Produto')
                ->using('App\ProdutoRent')
                ->withPivot('qtd', 'valor_aluguel','devolvido')
                ->withTimestamps();
    }
    
    public function cliente(){
        return $this->belongsTo("App\Cliente");
    }
    
     public function getDataSaidaAttribute($value)
    {
       return Carbon::parse($value)->format('d/m/Y');
    }
    
     public function formDataSaidaAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    
    public function setDataSaidaAttribute($value){
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if($value){
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $this->attributes['data_saida'] = Carbon::parse($value)->format('Y-m-d');
        } 
        else{
            $this->attributes['data_saida'] = null;
        }    
       
    }
    
     public function getDataRetornoAttribute($value)
    {
       return Carbon::parse($value)->format('d/m/Y');
    }
    
     public function formDataRetornoAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    
    public function setDataRetornoAttribute($value){
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if($value){
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $this->attributes['data_retorno'] = Carbon::parse($value)->format('Y-m-d');
        } 
        else{
            $this->attributes['data_retorno'] = null;
        }    
       
    }

    

    public function getProdutosJsonAttribute() {
         $x= $this->produtos;
         $list=[];
         foreach($this->produtos as $produto):
             $x= new \stdClass();
             $x->produto_id=$produto->id;
             $x->qtd=$produto->pivot->qtd;
             $x->valor_aluguel=$produto->pivot->valor_aluguel;
             $list[]=$x;
         endforeach;
         return json_encode($list);
         //return '[{"produto_id":"5","qtd":"37","valor_aluguel":"10.00"},{"produto_id":"4","qtd":"30","valor_aluguel":"1.00"}]';
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

    public function quitar(){
        $this->devolvido=1;
        $this->save();
       foreach($this->produtos as $produto){
           $produto->pivot->devolvido=1;
       }
       $retorno= $this->push();
       //Outra Forma de Gravar dados na tabela pivot 'produto_rent'
       //Nessa Forma o Observer não é disparado. Teria que Disparar Manualmente
        // $affected = \App\ProdutoRent::where('rent_id', $rent->id)
        //     ->update(['devolvido' => 1]);
      
       return $retorno;
    }

    public function desquitar(){
        $this->devolvido=0;
        $this->save();
       foreach($this->produtos as $produto){
           $produto->pivot->devolvido=0;
       }
       $retorno= $this->push();
       //Outra Forma de Gravar dados na tabela pivot 'produto_rent'
       //Nessa Forma o Observer não é disparado. Teria que Disparar Manualmente
        // $affected = \App\ProdutoRent::where('rent_id', $rent->id)
        //     ->update(['devolvido' => 0]);
      
       return $retorno;
    }

    public function getNomeStatus(){
        $st= $this->devolvido==0?"Em Aberto":"Devolvido";
        //considerando que se tem até meia noite para devolver;
        $dataRetorno=Carbon::createFromFormat('d/m/Y', $this->data_retorno)->endOfDay();
        $diaAtual=Carbon::now();
        if($this->devolvido==0 && $diaAtual->gt($dataRetorno) ):
            $st="<span class='text-danger'>Vencido</span>";
        endif;
       
        return $st;
    }

    public static function quitarBath($ids){
        $rents= self::whereIn('id',$ids)->get();
        $retorno=true;
        foreach($rents as $rent):
            $retorno= $retorno && $rent->quitar();
        endforeach;

        if(!$retorno):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Erro ao Quitar!"]);
        endif;
        return $retorno;
    }

    public static function desquitarBath($ids){
        $rents= self::whereIn('id',$ids)->get();
        $retorno=true;
        foreach($rents as $rent):
            $retorno= $retorno && $rent->desquitar();
        endforeach;

        if(!$retorno):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Erro ao Desquitar!"]);
        endif;
        return $retorno;
    }

}
