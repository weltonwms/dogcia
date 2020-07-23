<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome', 'descricao', 'valor_aluguel', 'valor_venda', 'qtd_estoque'];

    public function vendas()
    {
        return $this->belongsToMany('App\Venda')
            ->using('App\ProdutoVenda')
            ->withPivot('qtd', 'valor_venda')
            ->withTimestamps();
    }

    public function rents()
    {
        return $this->belongsToMany('App\Rent')
            ->wherePivot('devolvido', 0)
            ->using('App\ProdutoRent')
            ->withPivot('qtd', 'valor_aluguel', 'devolvido')
            ->withTimestamps();
    }

    public function countRents()
    {
        $produtosAlugados = $this->rents;
        $count = 0;
        foreach ($produtosAlugados as $produtoRent):
            $count += $produtoRent->pivot->qtd;
        endforeach;
        return $count;
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

    public function updateQtdDisponivel()
    {
        $this->qtd_disponivel= $this->qtd_estoque - $this->countRents();
        $this->save();
    }

    public function getFormatedValorAluguelAttribute()
    {
        return number_format($this->attributes['valor_aluguel'], 2, ',', '.');
    }

    public function getFormatedValorVendaAttribute()
    {
        return number_format($this->attributes['valor_venda'], 2, ',', '.');
    }

    public function setValorAluguelAttribute($price)
    {
        if (!is_numeric($price)):
            $price = str_replace(".", "", $price);
            $price = str_replace(",", ".", $price);
        endif;
        $this->attributes['valor_aluguel'] = $price;
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
        $nrRents= \App\ProdutoRent::whereIn("produto_id",$ids)->count();
        $nrVendas= \App\ProdutoVenda::whereIn("produto_id",$ids)->count();
        $nrTotal=$nrVendas+$nrRents;
        $msg=[];
        if($nrRents > 0):
            $msg[]="Produto(s) Relacionado(s) a Aluguel";
        endif;
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
        $nrRents=\App\ProdutoRent::where('produto_id', $this->id)->count(); //não uso pivot, porcausa do where devolvido==0
        $nrTotal=$nrVendas+$nrRents;
        if($nrTotal > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Produto(s) Relacionado(s) a Venda ou Aluguel"]);
            return false;
        else:
            return $this->delete();
        endif;
    }

}
