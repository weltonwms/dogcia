@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>'Produtos', 'icon'=>'fa-gift','route'=>route('produtos.index'),'subtitle'=>'Gerenciamento de Produtos'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('produtos.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('produtos/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('produtos_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th>Nome</th>
        <th>Valor Aluguel</th>
        <th>Valor Venda</th>
        <th>Qtd Estoque</th>
        <th>Qtd Disponível</th>
        <th>Descrição</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($produtos as $produto)
    <tr>
       
        <td></td>
        <td><a href="{{route('produtos.edit', $produto->id)}}">{{$produto->nome}}</a></td>
        <td>{{$produto->formated_valor_aluguel}}</td>
        <td>{{$produto->formated_valor_venda}}</td>
        <td>{{$produto->qtd_estoque}}</td>
        <td>{{$produto->qtd_disponivel}}</td>
        <td>{{$produto->descricao}}</td>
        <td>{{$produto->id}}</td>
    </tr>
    @endforeach
</tbody>
@enddatatables
@endsection

@push('scripts')

<script>
    /**
     * First start on Table
     * **********************************
     */
$(document).ready(function() {
    Tabela.getInstance({colId:7}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
</script>
@endpush