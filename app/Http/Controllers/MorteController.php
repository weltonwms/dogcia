<?php

namespace App\Http\Controllers;

use App\Morte;
use Illuminate\Http\Request;
use App\Http\Requests\MorteRequest;

class MorteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mortes = Morte::with('produto')->get();
        return view("mortes.index", compact('mortes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dados = [
            'produtos'=> \App\Produto::where('ser_vivo',1)->get(),
            'produtosList' => \App\Produto::getListVivos(),
            
            
        ];
        return view('mortes.create', $dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MorteRequest $request)
    {
        try{
            $morte=\DB::transaction(function () use ($request){
                return Morte::create($request->all());
                
            });

            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
            if ($request->input('fechar') == 1):
                return redirect()->route('mortes.index');
            endif;
            return redirect()->route('mortes.edit',$morte->id);
        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
            return redirect()->route('mortes.index');
        }
       
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Morte  $morte
     * @return \Illuminate\Http\Response
     */
    public function show(Morte $morte)
    {
        return $morte;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Morte  $morte
     * @return \Illuminate\Http\Response
     */
    public function edit(Morte $morte)
    {
        $dados = [
            'produtos'=> \App\Produto::where('ser_vivo',1)->get(),
            'produtosList' => \App\Produto::getListVivos(),
            'morte' => $morte,
        ];
        return view('mortes.edit', $dados);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Morte  $morte
     * @return \Illuminate\Http\Response
     */
    public function update(MorteRequest $request, Morte $morte)
    {
        try{
            \DB::transaction(function () use ($morte,$request){
                $morte->update($request->all());
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);

            });
        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
        }
        
        if ($request->input('fechar') == 1):
            return redirect()->route('mortes.index');
        endif;
        return redirect()->route('mortes.edit',$morte->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Morte  $morte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Morte $morte)
    {
        $retorno = $morte->delete();
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
        endif;
 
        return redirect()->route('mortes.index');
    }

    public function destroyBath()
     {
      $retorno= Morte::destroy(request('ids'));
      if ($retorno):
             \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
         endif;
 
         return redirect()->route('mortes.index');
     }

    //  private function getDadosTratados($request)
    //  {
    //      $produto= \App\Produto::find($request['produto_id']);
    //      $dados= $request;
    //      $dados['valor']=$produto->custo_medio;
        
    //      return $dados;
    //  }
}
