<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Compra;
use App\Morte;

class Produto extends Model
{
    protected $fillable = ['nome', 'descricao', 'ser_vivo', 'grandeza', 'valor_grandeza','margem'];

    public function vendas()
    {
        return $this->belongsToMany('App\Venda')
            ->using('App\ProdutoVenda')
            ->withPivot('qtd', 'granel', 'custo_medio', 'valor_venda')
            ->withTimestamps();
    }

   

    public function countVendas()
    {
        $produtosVendidos = $this->vendas;
        $count = 0;
        foreach ($produtosVendidos as $produtoVenda):
            $count += $produtoVenda->pivot->qtd;
        endforeach;
        return $count;
    }

    public static function getAllByFiltros(){
        $query = self::query();
        $request = request();
        if (is_numeric($request->vivo)):
            $query->where('ser_vivo', $request->vivo);
        endif;
        if (is_numeric($request->estoque)):
            $op= $request->estoque==0?"=":">";
            $query->where('qtd_estoque', $op, 0);
        endif;
        return $query->get();
        
    }

   

    public function getFormatedValorVendaAttribute()
    {
        return number_format($this->attributes['valor_venda'], 2, ',', '.');
    }

   

    public function setValorVendaAttribute($price)
    {
        if (!is_numeric($price)):
            $price = str_replace(".", "", $price);
            $price = str_replace(",", ".", $price);
        endif;
        $this->attributes['valor_venda'] = $price;
    }

    public function getNomeCompleto(){
        $nome=$this->nome;
            if($this->grandeza==1 || $this->grandeza==2):
                $grandezas=['','Kg','Lt'];
                $nome.= " ".$this->valor_grandeza." ".$grandezas[$this->grandeza];
            endif;
        return $nome;
    }

    public function getSerVivoTextoAttribute()
    {
       return $this->ser_vivo?"Sim":"Nao";
    }

    public function getNomeGrandeza()
    {
        $grandezas=['','Kg','Lt','Un'];
       return $grandezas[$this->grandeza];
    }

    public static function verifyAndDestroy(array $ids)
    {
        
        $nrVendas= \App\ProdutoVenda::whereIn("produto_id",$ids)->count();
        $nrTotal=$nrVendas+0;
        $msg=[];
       
        if($nrVendas > 0):
            $msg[]="Produto(s) Relacionado(s) a Venda";
        endif;
        if($nrTotal > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => implode("<br>",$msg)]);
            return false;
        else:
            return self::destroy($ids);
        endif;
    }

    public function verifyAndDelete()
    {
        $nrVendas=$this->vendas->count();
        
        $nrTotal=$nrVendas+0;

        if($nrTotal > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Produto(s) Relacionado(s) a Venda"]);
            return false;
        else:
            return $this->delete();
        endif;
    }

    public function getValorVenda(){
        
        return $this->custo_medio*($this->margem/100 +1);
    }

    public function getValorVendaGranel(){
        if($this->grandeza==1||$this->grandeza==2){
            return ($this->custo_medio/$this->valor_grandeza)*($this->margem/100 +1);
        }
        
    }

    public static function getList()
    {
        return self::all()->mapWithKeys(function($produto){
                  
            return [$produto->id => $produto->getNomeCompleto()];
        });
    }

    public static function getListVivos()
    {
        return self::where('ser_vivo',1)->get()->mapWithKeys(function($produto){
             return [$produto->id => $produto->getNomeCompleto()];
        });
    }

    /**
     * Método baseado em formula de Custo Médio. Recebe um total e qtd atual.
     * A formula basea-se no que está sendo adquirido, atualizado ou desfeito
     * e no que já tinha
     */
    public function setCustoMedioOnEvent($total, $qtd)
    {
        $numerador= $total + ($this->custo_medio * $this->qtd_estoque);
        $denoninador= $qtd + $this->qtd_estoque;
        $custo_medio= $denoninador==0?0:($numerador/$denoninador);
        $this->custo_medio=$custo_medio;
    }


    public function save(array $options = array())
    {
        if($this->qtd_estoque < 0):
            throw new \Exception('Estoque inválido.');
        endif;

        if($this->granel < 0):
            throw new \Exception('Granel inválido.');
        endif;
       
        parent::save($options);
    }
  

    

}
