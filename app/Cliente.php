<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
   
    protected $fillable = ['nome', 'email', 'telefone', 'cep', 'endereco', 'cpf'];
   

    public function vendas()
    {
        return $this->hasMany("App\Venda");
    }


    public function verifyAndDelete()
    {
        $nrVendas = $this->vendas->count();
        $nrTotal = $nrVendas + 0;
        if ($nrTotal > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Cliente(s) Relacionado(s) a Venda"]);
            return false;
        else:
            return $this->delete();
        endif;
    }

    public static function verifyAndDestroy(array $ids)
    {
       
        $nrVendas = \App\Venda::whereIn("cliente_id", $ids)->count();
        $nrTotal = $nrVendas + 0;
        $msg = [];
       
        if ($nrVendas > 0):
            $msg[] = "Cliente(s) Relacionado(s) a Venda";
        endif;
        if ($nrTotal > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => implode("<br>", $msg)]);
            return false;
        else:
            return self::destroy($ids);
        endif;
    }

}
