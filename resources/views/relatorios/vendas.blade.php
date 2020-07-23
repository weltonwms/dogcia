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
                <div class="col-md-4">
                    {{ Form::bsDate('data_venda1', request('data_venda1'),['label'=>'Data Venda >=']) }}
                </div>
                <div class="col-md-4">
                    {{ Form::bsDate('data_venda2',request('data_venda2'),['label'=>'Data Venda <=']) }}
                </div>

                <div class="col-md-4">
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

                    <button class="btn btn-outline-success pull-right"> Total Venda:
                        {{UtilHelper::moneyToBr($relatorio->total_venda)}}</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>Cód Venda</th>
                        <th>Cliente</th>
                        <th>Data Venda</th>
                        <th>Total</th>

                    </thead>

                    <tbody>
                        @foreach($relatorio->items as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->nome}}</td>
                            <td>{{$item->data_venda}}</td>
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