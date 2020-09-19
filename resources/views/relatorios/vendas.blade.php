@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>'Relatório: Vendas',
'icon'=>'fa-circle-o','route'=>route('relatorio.vendas'),'subtitle'=>'Relatório de
Vendas'])
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

            {!! Form::open(['route'=>'relatorio.vendas','id'=>'form_pesquisa'])!!}
            <div class="row">
                <div class="col-md-2" >
                    {{ Form::bsDate('data_venda1', request('data_venda1'),['label'=>'Data Venda >=', 'class'=>'form-control-sm']) }}
                </div>

                <div class="col-md-2" >
                    {{ Form::bsDate('data_venda2', request('data_venda2'),['label'=>"Data Venda <=", 'class'=>'form-control-sm']) }}
                </div>

                <div class="col-md-2">
                    {{ Form::bsSelect('status', [''=>"Todos",'1'=>"Pago",'2' => 'Não Pago'],
                    request('status') , ['class'=>'form-control-sm']
                    ) }}
                   
                </div>



               

                <div class="col-md-3">
                    {{ Form::bsSelect('cliente_id[]',$clientes,request('cliente_id'),['label'=>"Cliente(s)", 
                    'class'=>'select2','multiple'=>'multiple']) }}
                </div>

                <div class="col-md-3">
                    {{ Form::bsSelect('seller_id[]',$sellers,request('seller_id'),['label'=>"Vendedor(es)", 
                    'class'=>'select2','multiple'=>'multiple']) }}
                </div>


                {{-- <button type="submit">Env</button> --}}

            </div>

            <div class="row">
               

                

                <div class="col-md-2">
                    {{ Form::bsSelect('frete', [''=>"Todos",'1'=>"Sim",'0' => 'Não'],
                    request('frete'),  ['class'=>'form-control-sm']
                    ) }}
                </div>

                <div class="col-md-2">
                    {{ Form::bsSelect('carteira', [''=>"Todos",'1'=>"Sim",'0' => 'Não'],
                    request('carteira') ,  ['class'=>'form-control-sm']
                    ) }}
                 </div>

                 <div class="col-md-2">
                    {{ Form::bsSelect('forma_pagamento', [''=>"Todos",'1'=>"Dinheiro",'2' => 'Cartão Crédito','3'=>'Cartão Débito'],
                    request('forma_pagamento') , ['class'=>'form-control-sm']
                    ) }}
                   
                </div>


            </div>
            {!! Form::close() !!}



            <div class="row">
                <div class="col-md-12 ">
                    @if($relatorio->items)
                    <span class="text-primary"><b>Mostrando {{$relatorio->items->count()}} Registro(s)</b></span>
                    @endif

                    <button class="btn btn-outline-success pull-right"> Total Lucro:
                        {{UtilHelper::moneyToBr($relatorio->totalLucro)}}
                    </button>
                    <button class="btn btn-outline-success pull-right mr-2"> Total Venda:
                        {{UtilHelper::moneyToBr($relatorio->total_venda)}}
                    </button>
                    <button class="btn btn-outline-danger pull-right mr-2"> Total Custo:
                        {{UtilHelper::moneyToBr($relatorio->total_custo)}}
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>Cód Venda</th>
                        <th>Cliente</th>
                        <th>Data Venda</th>
                        <th>Vendedor</th>
                        <th>Frete</th>
                        <th>Carteira</th>
                        <th>Status</th>
                        <th>Forma Pagamento</th>
                        <th>Total Custo</th>
                        <th>Total Venda</th>
                        <th>Lucro</th>

                    </thead>

                    <tbody>
                        @foreach($relatorio->items as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->cliente_nome}}</td>
                            <td>{{$item->data_venda}}</td>
                            
                            <td>{{$item->seller_nome}}</td>
                            <td>{{$item->frete}}</td>
                            <td>{{$item->carteira}}</td>
                            <td>{{$item->status}}</td>
                            <td>{{$item->forma_pagamento}}</td>

                            <td>{{UtilHelper::moneyToBr($item->total_custo)}}</td>
                            <td>{{UtilHelper::moneyToBr($item->total_venda)}}</td>
                            <td>{{UtilHelper::moneyToBr($item->lucro)}}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>



@endsection