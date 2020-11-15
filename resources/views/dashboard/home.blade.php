@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>'Painel de Controle',
'icon'=>'fa-dashboard','route'=>route('home'),'subtitle'=>'Página Inicial do
Sistema Dog e Cia'])
@endbreadcrumbs
@endsection


@section('content')
<style>
  .tile.tile-mensagens {
    display: none;
  }
</style>


{{-- inicio cards --}}
@include('dashboard.cards')
{{-- temino cards --}}


<div class="row">

  <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title"><i class="fa fa-exclamation-triangle text-danger"></i> Fretes Não Pagos</h3>
      @if(count($fretesNaoPagos))
        <ul>
          @foreach($fretesNaoPagos as $venda)
          <li>
            <a href="{{route('vendas.edit', $venda->id)}}">
              {{$venda->cliente->nome}} | {{$venda->data_venda}} | Venda {{$venda->id}} - {{$venda->seller->nome}}
            </a>
          </li>
          @endforeach
        </ul>
      @else
        <h3 class="ml-5">0</h3>
      @endif
    </div>
  </div>



</div>

{{-- inicio charts --}}
@include('dashboard.charts')
{{-- temino charts --}}



@endsection