<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome', 'descricao', 'ser_vivo', 'grandeza', 'valor_grandeza','margem'];

    public function vendas()
    {
        return $this->belongsToMany('App\Venda')
            ->using('App\ProdutoVenda')
            ->withPivot('qtd', 'valor_venda')
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

}
