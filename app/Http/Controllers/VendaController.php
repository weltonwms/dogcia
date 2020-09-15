<?php

namespace App\Http\Controllers;

use App\Helpers\ProdutoHelper;
use App\Http\Requests\VendaRequest;
use App\Venda;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $vendas = Venda::getAllByFiltros();
       return view("vendas.index", compact('vendas'));
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
            'sellers' => \App\Seller::pluck('nome', 'id'),
        ];
        return view('vendas.create', $dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendaRequest $request)
    {
        $ctrl=$this;
        try{
            $venda=\DB::transaction(function () use ($request, $ctrl){
                $venda = Venda::create($request->all());
                $ctrl->saveProdutos($venda, $request);
                return $venda;
            });
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
            if ($request->input('fechar') == 1):
                return redirect()->route('vendas.index');
            endif;
            return redirect()->route('vendas.edit',$venda->id);

        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
            return redirect()->back()->withInput();
        }
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function show(Venda $venda)
    {
        return $venda->load('produtos');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function edit(Venda $venda)
    {
        $dados = [
            'produtos' => \App\Produto::all(),
            'clientes' => \App\Cliente::pluck('nome', 'id'),
            'sellers' => \App\Seller::pluck('nome', 'id'),
            'venda' => $venda,
        ];
        return view('vendas.edit', $dados);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function update(VendaRequest $request, Venda $venda)
    {
        $ctrl=$this;
        try{
            \DB::transaction(function () use ($venda, $request, $ctrl){
                $venda->update($request->all());
                $ctrl->saveProdutos($venda, $request);
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
            });

        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
        }
       
        if ($request->input('fechar') == 1):
            return redirect()->route('vendas.index');
        endif;
        return redirect()->route('vendas.edit',$venda->id);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venda $venda)
    {
        try{
            \DB::transaction(function () use ($venda){
                $venda->delete();
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
            });

        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
        }
        
        return redirect()->route('vendas.index');
    }

    public function destroyBath()
    {
       try{
            \DB::transaction(function () {
                $retorno=Venda::destroy(request('ids'));
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);

            });
        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
        }

        return redirect()->route('vendas.index');

    }


    private function saveProdutos($venda, $request)
    {
        $produtos = json_decode($request->produtos_json, true);
        $dados = [];

        //montar array com índice sendo o produto_id. Ex: 3=>['qtd'=>10,'valor_venda'=>100,'custo_medio'=>50]
        foreach ($produtos as $produto):
            $product = $produto;
            unset($product['produto_id']);
            $product['granel']= (isset($produto['granel']) && $produto['granel']==1)?1:0;
            $dados[$produto['produto_id']] = $product;
        endforeach;
        $venda->produtos()->sync($dados);

    }

    function print(Venda $venda) {
        return view('vendas.print', compact('venda'));
    }

    public function detailAjax(Venda $venda)
    {
       return view('vendas.detail', compact('venda'));
    }


}
