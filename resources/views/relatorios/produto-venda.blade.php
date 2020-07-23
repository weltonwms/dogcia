@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>'Relatório: Produtos Vendidos',
'icon'=>'fa-circle-o','route'=>route('relatorio.produtoVenda'),'subtitle'=>'Relatório de
Produtos Vendidos'])
@endbreadcrumbs
@endsection
@section('toolbar')
@toolbar
<button class="btn btn-sm btn-primary mr-1 mb-1" onclick="document.getElementById('form_pesquisa').submit()">
    <i class="fa fa-search" aria-hidden="true"></i> Executar Pesquisa
</button>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" onclick="limparFormPesquisa()">
    <i class="fa fa-undo"></i>Limpar Form Pesquisa
</button>

{{-- <button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" target="_blank">
    <i class="fa fa-print"></i>Imprimir
</button> --}}
@endtoolbar
@endsection


@section('content')
<?php
use App\Helpers\UtilHelper;
?>
<div class="row">
    <div class="col-md-12">
        <div class="tile">

            {!! Form::open(['route'=>'relatorio.produtoVenda','id'=>'form_pesquisa'])!!}
            <div class="row">
                <div class="col-md-3">
                    {{ Form::bsDate('data_venda1', request('data_venda1'),['label'=>'Data Venda >=']) }}
                </div>
                <div class="col-md-3">
                    {{ Form::bsDate('data_venda2',request('data_venda2'),['label'=>'Data Venda <=']) }}
                </div>



                <div class="col-md-3">
                    {{ Form::bsSelect('produto_id[]',$produtos,request('produto_id'),['label'=>"Produto(s)", 
                    'class'=>'select2','multiple'=>'multiple']) }}
                </div>

                <div class="col-md-3">
                    {{ Form::bsSelect('cliente_id[]',$clientes,request('cliente_id'),['label'=>"Cliente(s)", 
                    'class'=>'select2','multiple'=>'multiple']) }}
                </div>


                {{-- <button type="submit">Env</button> --}}

            </div>
            </form>



            <div class="row">
                <div class="col-md-12 ">
                    @if($relatorio->items)
                    <span class="text-primary"><b>Mostrando {{$relatorio->items->count()}} Registro(s)</b></span>
                    @endif
                    <button class="btn btn-outline-success pull-right"> Total Geral:
                        {{UtilHelper::moneyToBr($relatorio->total)}}</button>
                    <button class="btn btn-outline-info mr-2 pull-right"> Total Qtd: {{$relatorio->totalQtd}}</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>Cód Venda</th>
                        <th>Cliente</th>
                        <th>Data Venda</th>

                        <th>Cód Produto</th>
                        <th>Nome Produto</th>
                        <th>Qtd</th>
                        <th>Valor Venda</th>
                        <th>Total</th>

                    </thead>

                    <tbody>
                        @foreach($relatorio->items as $item)
                        <tr>
                            <td>{{$item->venda_id}}</td>
                            <td>{{$item->cliente_nome}}</td>
                            <td>{{UtilHelper::dateBr($item->data_venda)}}</td>

                            <td>{{$item->produto_id}}</td>
                            <td>{{$item->produto_nome}}</td>
                            <td>{{$item->qtd}}</td>
                            <td>{{UtilHelper::moneyToBr($item->valor_venda)}}</td>
                            <td>{{UtilHelper::moneyToBr($item->total)}}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>




        </div>
    </div>

</div>




@endsection