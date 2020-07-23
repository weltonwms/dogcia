<?php

namespace App;

use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use FormAccessible;
    protected $fillable = ['nome', 'email', 'telefone', 'nascimento', 'cep', 'endereco', 'cpf'];
    protected $dates = array('nascimento');

    public function rents()
    {
        return $this->hasMany("App\Rent");
    }

    public function vendas()
    {
        return $this->hasMany("App\Venda");
    }

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

    public function verifyAndDelete()
    {
        $nrVendas = $this->vendas->count();
        $nrRents = $this->rents->count();
        $nrTotal = $nrVendas + $nrRents;
        if ($nrTotal > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Cliente(s) Relacionado(s) a Venda ou Aluguel"]);
            return false;
        else:
            return $this->delete();
        endif;
    }

    public static function verifyAndDestroy(array $ids)
    {
        $nrRents = \App\Rent::whereIn("cliente_id", $ids)->count();
        $nrVendas = \App\Venda::whereIn("cliente_id", $ids)->count();
        $nrTotal = $nrVendas + $nrRents;
        $msg = [];
        if ($nrRents > 0):
            $msg[] = "Cliente(s) Relacionado(s) a Aluguel";
        endif;
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
