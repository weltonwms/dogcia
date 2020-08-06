<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Morte extends Model
{
    protected $fillable = ['produto_id', 'qtd', 'valor'];

    public function produto(){
        return $this->belongsTo("App\Produto");
    }

    public function getTotal(){
        return $this->valor*$this->qtd;
    }

    
   
}
