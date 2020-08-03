@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>' Vendedores', 'icon'=>'fa-meh-o', 'route'=>route('sellers.index'),'subtitle'=>'Gerenciamento de  Vendedores'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('sellers.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('sellers/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('sellers_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th>Nome</th>
        <th>Nascimento</th>
        <th>Início de Trabalho</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($sellers as $seller)
    <tr>
        <td></td>
        <td><a href="{{route('sellers.edit', $seller->id)}}">{{$seller->nome}}</a></td>
        <td>{{$seller->nascimento}}</td>
        <td>{{$seller->inicio_trabalho}}</td>
        <td>{{$seller->id}}</td>
    </tr>
    @endforeach
</tbody>
@enddatatables
@endsection

@push('scripts')

<script>
    /*
     * First start on Table
     * **********************************
     */
$(document).ready(function() {
    Tabela.getInstance({colId:4}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
</script>
@endpush