@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>'Vendas', 'icon'=>'fa-cart-plus','route'=>route('vendas.index'),'subtitle'=>'Gerenciamento de
Vendas'])

@endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('vendas.create')}}">
    <i class="fa fa-plus-circle"></i>Novo
</a>

<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link"
    data-route="{{url('vendas/{id}/edit')}}" onclick="dataTableSubmit(event)">
    <i class="fa fa-pencil"></i>Editar
</button>

<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" target="_blank"
    data-route="{{url('vendas/{id}/print')}}" onclick="dataTableSubmit(event)">
    <i class="fa fa-print"></i>Imprimir
</button>


<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete"
    data-route="{{route('vendas_bath.destroy')}}" onclick="dataTableSubmit(event)">
    <i class="fa fa-trash"></i>Excluir
</button>

@endtoolbar
@endsection

@section('content')



@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th>ID</th>
        <th>Cliente</th>
        <th>Data Venda</th>

        <th>Observação</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
    @foreach($vendas as $venda)
    <tr>

        <td></td>
        <td>
            <button onclick="showDetailVenda(event)" data-id="{{$venda->id}}" class="btn btn-light btn-sm"><i class="fa fa-eye"></i></button>
            <a href="{{route('vendas.edit', $venda->id)}}">{{$venda->id}}</a>
        </td>
        <td>{{$venda->cliente->nome}}</td>
        <td>{{$venda->data_venda}}</td>

        <td>{{$venda->observacao}}</td>
        <td>{{$venda->id}}</td>
    </tr>
    @endforeach
</tbody>
@enddatatables

<!--Modal de Detalhe da Venda -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_detalhar_venda">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fa fa-eye "></i> Detalhes da Venda</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Texto do corpo do modal, é aqui.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
<!--Fim de Detalhe da Venda -->



@endsection

@push('scripts')
<script>
    /**
     * First start on Table
     * **********************************
     */
$(document).ready(function() {
    Tabela.getInstance({colId:5}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
function showDetailVenda(event){
    event.preventDefault();
    var dados= event.currentTarget.dataset;
    $.ajax({
        dataType:"html",
        url:"vendas/"+dados.id+"/detailAjax",
        success:function(data){
            $('#modal_detalhar_venda .modal-body').html(data);
            $("#modal_detalhar_venda").modal('show');
        }
    });
}
 

</script>

@endpush