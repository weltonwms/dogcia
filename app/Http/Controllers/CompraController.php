<?php

namespace App\Http\Controllers;

use App\Compra;
use Illuminate\Http\Request;
use App\Http\Requests\CompraRequest;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compras = Compra::getAllByFiltros();
        $produtos= \App\Produto::getList();
        return view("compras.index", compact('compras','produtos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dados = [
            'produtos' => \App\Produto::getList(),
        ];
        return view('compras.create', $dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompraRequest $request)
    {
        $compra = Compra::create($request->all());
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('compras.index');
        elseif($request->input('fechar') == 2):
            return redirect()->route('compras.create');
        endif;
        return redirect()->route('compras.edit',$compra->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function show(Compra $compra)
    {
        return $compra;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit(Compra $compra)
    {
        $dados = [
            'produtos' => \App\Produto::getList(),
            'compra' => $compra,
        ];
        return view('compras.edit', $dados);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compra $compra)
    {
        try{
            \DB::transaction(function () use ($compra,$request){
                $compra->update($request->all());
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);

            });
        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
        }
        
        if ($request->input('fechar') == 1):
            return redirect()->route('compras.index');
        elseif($request->input('fechar') == 2):
                return redirect()->route('compras.create');
        endif;
        
        return redirect()->route('compras.edit',$compra->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Compra $compra)
    {
        try{
            \DB::transaction(function () use ($compra){
                $compra->delete();
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
            });

        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
        }
         return redirect()->route('compras.index');

     }
 
     public function destroyBath()
     {
        try{
            \DB::transaction(function () {
                $retorno=Compra::destroy(request('ids'));
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);

            });
        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
        }
        return redirect()->route('compras.index');
        
     }
}
