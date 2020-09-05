<?php

namespace App;

use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Model;


class Compra extends Model
{
    use FormAccessible;
    protected $fillable = ['produto_id', 'qtd', 'data_compra', 'valor_compra','vencimento'];
    protected $dates = array('data_compra','vencimento');

    public function produto(){
        return $this->belongsTo("App\Produto");
    }

    public static function getAllByFiltros(){
        $query = self::with('produto');
        $request = request();
        if ($request->produto_id):
            $query->where('produto_id', $request->produto_id);
        endif;
       
        return $query->get();
        
    }

    public function getVencimentoAttribute($value)
    {
        if ($value):
            //evitando fazer um parse em nada. Não seria necessário se campo fosse obrigatório
            return Carbon::parse($value)->format('d/m/Y');
            //return Carbon::parse($value)->format('Y-m-d');
        endif;
    }

    public function formVencimentoAttribute($value)
    {
        if ($value):
            return Carbon::parse($value)->format('Y-m-d');
        endif;

    }

    public function setVencimentoAttribute($value)
    {
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if ($value) {
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $this->attributes['vencimento'] = Carbon::parse($value)->format('Y-m-d');
        } else {
            $this->attributes['vencimento'] = null;
        }

    }
    
    public function getDataCompraAttribute($value)
    {
       return Carbon::parse($value)->format('d/m/Y');
    }
    
     public function formDataCompraAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    
    public function setDataCompraAttribute($value){
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if($value){
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $this->attributes['data_compra'] = Carbon::parse($value)->format('Y-m-d');
        } 
        else{
            $this->attributes['data_compra'] = null;
        }    
       
    }

    public function getFormatedValorCompraAttribute()
    {
        return number_format($this->attributes['valor_compra'], 2, ',', '.');
    }

    public function setValorCompraAttribute($price)
    {
        if (!is_numeric($price)):
            $price = str_replace(".", "", $price);
            $price = str_replace(",", ".", $price);
        endif;
        $this->attributes['valor_compra'] = $price;
    }

    public function getTotal(){
        return $this->qtd*$this->valor_compra;
    }

    

    
    



}
