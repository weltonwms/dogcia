<?php

namespace App;

use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Model;

class Morte extends Model
{
    use FormAccessible;
    protected $fillable = ['produto_id', 'qtd', 'data_morte', 'custo_medio'];
    protected $dates = array('data_morte');

    public function produto(){
        return $this->belongsTo("App\Produto");
    }

    public function getDataMorteAttribute($value)
    {
       return Carbon::parse($value)->format('d/m/Y');
    }
    
     public function formDataMorteAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function formCustoMedioAttribute($value)
    {
        return \Util::formatNumber($value);
    }
    
    public function setDataMorteAttribute($value){
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if($value){
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $this->attributes['data_morte'] = Carbon::parse($value)->format('Y-m-d');
        } 
        else{
            $this->attributes['data_morte'] = null;
        }    
       
    }

    public function getFormatedCustoMedioAttribute()
    {
        return number_format($this->attributes['custo_medio'], 2, ',', '.');
    }

    public function setCustoMedioAttribute($price)
    {
        if (!is_numeric($price)):
            $price = str_replace(".", "", $price);
            $price = str_replace(",", ".", $price);
        endif;
        $this->attributes['custo_medio'] = $price;
    }

    public function getTotal(){
        return $this->qtd*$this->custo_medio;
    }

    
   
}
