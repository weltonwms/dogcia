<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Produto;
use \App\Grano;

class GranoController extends Controller
{
   public function edit(Produto $produto)
   {
       if($produto->ser_vivo){
        \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Operação Negada para Ser Vivo"]);
        return redirect()->back();
       }
        $granos=Grano::where('produto_id',$produto->id)->get();
        $granelVendido=Grano::getQtdVendas($produto->id);
        $granelDisponibilizado=Grano::getTotalValor($granos);
        return view('produtos.granel.edit', compact('produto','granos','granelVendido','granelDisponibilizado'));
   }

   public function store(Request $request)
    {
        $produto=Produto::find($request->produto_id);
        Grano::addGranel($produto);
        return redirect()->route('produtos_granel.edit', $produto->id);
    }

    public function destroy(Grano $grano)
    {
        $produto= $grano->produto;
        $grano->removeGranel();
        return redirect()->route('produtos_granel.edit', $produto->id);
    
    }
}
