<?php

namespace App\Http\Controllers;

use App\Rent;
use Illuminate\Http\Request;
use App\Http\Requests\RentRequest;

class RentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $st=request('st',0);
        session(['st'=>$st]); //útil para proximas requisições manter na mesma tela
        $rents = Rent::with('cliente')->where('devolvido',$st)->get();
        return view("rents.index", compact('rents','st'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $dados = [
            'produtos' => \App\Produto::all(),
            'clientes' => \App\Cliente::pluck('nome', 'id'),
        ];
        return view('rents.create', $dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RentRequest $request)
    {
         $rent= Rent::create($request->all());
         $rent->devolvido=0; //$rent original não possui info de devolvido
         $this->saveProdutos($rent, $request);
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('rents.index');
        endif;
        return redirect()->route('rents.edit',$rent->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function show(Rent $rent)
    {
       return $rent->load('produtos');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function edit(Rent $rent)
    {
       $dados = [
            'produtos' => \App\Produto::all(),
            'clientes' => \App\Cliente::pluck('nome', 'id'),
             'rent'=>$rent
        ];
        return view('rents.edit', $dados);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function update(RentRequest $request, Rent $rent)
    {
        
         $rent->update($request->all());
         $this->saveProdutos($rent, $request);
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('rents.index',['st'=>session()->get('st')]);
        endif;
        return redirect()->route('rents.edit',$rent->id);
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rent $rent)
    {
        $produtos=$rent->produtos;
        //$retorno = $rent->verifyAndDelete();
       $retorno = $rent->delete();
       if ($retorno):
        \App\Helpers\ProdutoHelper::updateQtdDisponivelByProdutos($produtos);
           \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
       endif;

       return redirect()->route('rents.index',['st'=>session()->get('st')]);
    }
    
    public function destroyBath()
    {
     $produtos= \App\Helpers\ProdutoHelper::getProdutosByRentsIds(request('ids'));
     $retorno= Rent::destroy(request('ids'));
     if ($retorno):
        \App\Helpers\ProdutoHelper::updateQtdDisponivelByProdutos($produtos);
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;

        return redirect()->route('rents.index',['st'=>session()->get('st')]);
    }
    
    private function saveProdutos($rent, $request)
    {
        $produtos= json_decode($request->produtos_json, true);
        $dados= [];
        
        //montar array com índice sendo o produto_id. Ex: 3=>['qtd'=>10,'valor_aluguel'=>100,'devolvido'=>0]
        foreach($produtos as $produto):
            $product=$produto;
            $product['devolvido']= (int) $rent->devolvido;
            unset($product['produto_id']);
            $dados[$produto['produto_id']]=$product;
            
        endforeach;
        $rent->produtos()->sync($dados);
        
    }

    public function print(Rent $rent){
       
        return view('rents.print', compact('rent'));
    }

    public function quitar(Rent $rent){
       if($rent->quitar()):
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionQuitar',1)]);
       endif;

       return redirect()->route('rents.index');
  
    }

    public function desquitar(Rent $rent){
        if($rent->desquitar()):
         \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDesquitar',1)]);
        endif;
 
        return redirect()->route('rents.index',['st'=>session()->get('st')]);
   
     }

     public function quitarBath(){
        $retorno=Rent::quitarBath(request('ids'));
        $count=count(request('ids'));
        if($retorno){
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionQuitar',$count)]);
        }
        return redirect()->route('rents.index');
     }

     public function desquitarBath(){
        $retorno=Rent::desquitarBath(request('ids'));
        $count=count(request('ids'));
        if($retorno){
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDesquitar',$count)]);
        }
        return redirect()->route('rents.index',['st'=>session()->get('st')]);
     }

     public function detailAjax(Rent $rent)
     {
        return view('rents.detail', compact('rent'));
     }

}
