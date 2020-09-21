@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>'Relatório: Vendedores',
'icon'=>'fa-circle-o','route'=>route('relatorio.sellers'),'subtitle'=>'Relatório de
Vendedores'])
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
<?php 
    $ordenadores=[1=>"Lucro Maior", 2=>"Venda Maior",3=>"Lucro Menor",4=>"Venda Menor"]
?>

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="tile">

            {!! Form::open(['route'=>'relatorio.sellers','id'=>'form_pesquisa'])!!}
            <div class="row">
                <div class="col-md-3">
                    {{ Form::bsDate('data_venda1', request('data_venda1'),['label'=>'Data Venda >=']) }}
                </div>
                <div class="col-md-3">
                    {{ Form::bsDate('data_venda2',request('data_venda2'),['label'=>'Data Venda <=']) }}
                </div>

               

                <div class="col-md-4">
                    {{ Form::bsSelect('seller_id[]',$sellers,request('seller_id'),['label'=>"Vendedor(es)", 
                    'class'=>'select2','multiple'=>'multiple']) }}
                </div>

                <div class="col-md-2">
                    {{ Form::bsSelect('ordenado_por',$ordenadores,request('ordenado_por')) }}
                </div>

 



            </div>
            </form>



            <div class="row">
                <div class="col-md-12 ">
                    @if($relatorio->items)
                    <span class="text-primary"><b>Mostrando {{$relatorio->items->count()}} Registro(s)</b></span>
                    @endif
                    <button class="btn btn-outline-success pull-right"> Geral Lucro:
                        {{Util::moneyToBr($relatorio->totalLucro)}}
                    </button>
                    <button class="btn btn-outline-success pull-right"> Geral Venda:
                        {{Util::moneyToBr($relatorio->total_venda)}}
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>#</th>
                        <th>Cód Vendedor</th>
                        <th>Nome</th>
                        <th>Total Venda</th>
                        <th>Lucro</th>

                       

                    </thead>

                    <tbody>
                        @foreach($relatorio->items as $key=>$item)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{$item->seller_id}}</td>
                            <td>{{$item->nome_vendedor}}</td>
                            <td>{{Util::moneyToBr($item->total_venda)}}</td>
                            <td>{{Util::moneyToBr($item->lucro)}}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>




        </div>
    </div>

</div>




@endsection