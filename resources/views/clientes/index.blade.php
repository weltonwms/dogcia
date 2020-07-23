@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>'Clientes', 'icon'=>'fa-users', 'route'=>route('clientes.index'),'subtitle'=>'Gerenciamento de Clientes'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('clientes.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('clientes/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('clientes_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Nascimento</th>
        <th>Endereço</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($clientes as $cliente)
    <tr>
        <td></td>
        <td><a href="{{route('clientes.edit', $cliente->id)}}">{{$cliente->nome}}</a></td>
        <td>{{$cliente->email}}</td>
        <td>{{$cliente->telefone}}</td>
        <td>{{$cliente->nascimento}}</td>
        <td>{{$cliente->endereco}}</td>
        <td>{{$cliente->id}}</td>
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
    Tabela.getInstance({colId:6}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
</script>
@endpush