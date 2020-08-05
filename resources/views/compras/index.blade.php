@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>'Compras', 'icon'=>'fa-gift','route'=>route('compras.index'),'subtitle'=>'Gerenciamento de
Compras'])

@endbreadcrumbs
@endsection

@section('toolbar')
@toolbar

<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('compras.create')}}">
    <i class="fa fa-plus-circle"></i>Novo
</a>


<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link"
    data-route="{{url('compras/{id}/edit')}}" onclick="dataTableSubmit(event)">
    <i class="fa fa-pencil"></i>Editar
</button>

<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete"
    data-route="{{route('compras_bath.destroy')}}" onclick="dataTableSubmit(event)">
    <i class="fa fa-trash"></i>Excluir
</button>

@endtoolbar
@endsection

@section('content')

@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th>Produto</th>
        <th>Data Compra</th>
        <th>Data Vencimento</th>
        <th>Qtd</th>
        <th>Valor Un</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
    @foreach($compras as $compra)
    <tr>

        <td></td>
        <td>
          <a href="{{route('compras.edit', $compra->id)}}">
            {{$compra->produto->getNomeCompleto()}}
        </a>
        </td>
        <td>{{$compra->data_compra}}</td>
        <td>{{$compra->vencimento}}</td>
        <td>{{$compra->qtd}}</td>
        <td>{{$compra->valor_compra}}</td>
        <td>{{$compra->id}}</td>
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
    Tabela.getInstance({colId:6}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//

 

</script>

@endpush