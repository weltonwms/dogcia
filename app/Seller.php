<?php

namespace App;

use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use FormAccessible;
    protected $fillable = ['nome', 'nascimento', 'inicio_trabalho'];
    protected $dates = array('nascimento', 'inicio_trabalho');


    public function getNascimentoAttribute($value)
    {
        if ($value):
            //evitando fazer um parse em nada. Não seria necessário se campo fosse obrigatório
            return Carbon::parse($value)->format('d/m/Y');
            //return Carbon::parse($value)->format('Y-m-d');
        endif;
    }

    public function formNascimentoAttribute($value)
    {
        if ($value):
            return Carbon::parse($value)->format('Y-m-d');
        endif;

    }

    public function setNascimentoAttribute($value)
    {
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if ($value) {
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $this->attributes['nascimento'] = Carbon::parse($value)->format('Y-m-d');
        } else {
            $this->attributes['nascimento'] = null;
        }

    }



    public function getInicioTrabalhoAttribute($value)
    {
        if ($value):
            //evitando fazer um parse em nada. Não seria necessário se campo fosse obrigatório
            return Carbon::parse($value)->format('d/m/Y');
            //return Carbon::parse($value)->format('Y-m-d');
        endif;
    }

    public function formInicioTrabalhoAttribute($value)
    {
        if ($value):
            return Carbon::parse($value)->format('Y-m-d');
        endif;

    }

    public function setInicioTrabalhoAttribute($value)
    {
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if ($value) {
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $this->attributes['inicio_trabalho'] = Carbon::parse($value)->format('Y-m-d');
        } else {
            $this->attributes['inicio_trabalho'] = null;
        }

    }

    public function verifyAndDelete()
    {
        $nrVendas = $this->vendas->count();
        $nrTotal = $nrVendas + 0;
        if ($nrTotal > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Vendedor(es) Relacionado(s) a Venda"]);
            return false;
        else:
            return $this->delete();
        endif;
    }

    public static function verifyAndDestroy(array $ids)
    {
       
        $nrVendas = \App\Venda::whereIn("seller_id", $ids)->count();
        $nrTotal = $nrVendas + 0;
        $msg = [];
       
        if ($nrVendas > 0):
            $msg[] = "Vendedor(es) Relacionado(s) a Venda";
        endif;
        if ($nrTotal > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => implode("<br>", $msg)]);
            return false;
        else:
            return self::destroy($ids);
        endif;
    }

   
}
