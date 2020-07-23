@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>'Relatório: Alugueis',
'icon'=>'fa-circle-o','route'=>route('relatorio.rents'),'subtitle'=>'Relatório de
Alugueis'])
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

            {!! Form::open(['route'=>'relatorio.rents','id'=>'form_pesquisa'])!!}
            <div class="row">
                <div class="col-md-3">
                    {{ Form::bsDate('data_saida1', request('data_saida1'),['label'=>'Data Saída >=']) }}
                </div>
                <div class="col-md-3">
                    {{ Form::bsDate('data_saida2',request('data_saida2'),['label'=>'Data Saída <=']) }}
                </div>

                <div class="col-md-3">
                    {{ Form::bsDate('data_retorno1',request('data_retorno1'),['label'=>'Data Retorno >=']) }}
                </div>
                <div class="col-md-3">
                    {{ Form::bsDate('data_retorno2',request('data_retorno2'),['label'=>'Data Retorno <=']) }}
                </div>


                <div class="col-md-3">
                    {{ Form::bsSelect('cliente_id[]',$clientes,request('cliente_id'),['label'=>"Cliente(s)", 
                    'class'=>'select2','multiple'=>'multiple']) }}

                </div>



                <div class="col-md-3">
                    {{ Form::bsSelect('status',['Em aberto','Devolvido'],request('status'),['placeholder'=>'-Selecione-']) }}
                </div>

                {{-- <button type="submit">Env</button> --}}

            </div>
            </form>



            <div class="row">
                <div class="col-md-12 ">
                    @if($relatorio->items)
                    <span class="text-primary"><b>Mostrando {{$relatorio->items->count()}} Registro(s)</b></span>
                    @endif

                    <button class="btn btn-outline-success pull-right"> Total Aluguel:
                        {{UtilHelper::moneyToBr($relatorio->total_rent)}}</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>Cód Aluguel</th>
                        <th>Status</th>

                        <th>Cliente</th>
                        <th>Data Saída</th>
                        <th>Data Retorno</th>

                        <th>Total</th>

                    </thead>

                    <tbody>
                        @foreach($relatorio->items as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{!!$item->getNomeStatus()!!}</td>

                            <td>{{$item->nome}}</td>
                            <td>{{$item->data_saida}}</td>
                            <td>{{$item->data_retorno}}</td>

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