@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>'Compras', 'icon'=>'fa-shopping-basket','route'=>route('compras.index'),'subtitle'=>'Gerenciamento de
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


<div class="tile tile-nomargin">
    <form action="{{route('compras.index')}}">
        <label class="text-primary">Produto</label>
        {!!Form::select('produto_id', $produtos,
        request('produto_id'),
        ['onchange'=>"this.form.submit()","class"=>"select2","placeholder"=>"-Selecione-"]
        )!!}
        &nbsp;&nbsp;
        
        <label class="text-primary">Custo Compras</label>
       <button type="button" class="btn btn-outline-info btn-sm">R$ <span id="custo_compras">0,00</span></button>
       

    </form>
</div>

@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th>Produto</th>
        <th>Data Compra</th>
        <th>Data Vencimento</th>
        <th>Qtd</th>
        <th>Valor Un</th>
        <th>Total</th>
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
        <td>{{Util::moneyToBr($compra->valor_compra,true)}}</td>
        <td>{{Util::moneyToBr($compra->getTotal(),true)}}</td>
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
    var tabela=Tabela.getInstance({colId:7}); //instanciando dataTable e informando a coluna do id
    writeCustoCompras();
    tabela.on( 'search.dt', function () {
        writeCustoCompras();
    } );
});
   //fim start Datatable//

   function getTotalCompras(){
    function filtroCelula(celula){
        return parseFloat(celula.replace("R$", "").trim().replace(".","").replace(",","."));
    }
    var tabela= Tabela.getInstance();
    var total=0;
    tabela.rows({filter:'applied'}).data().each(function(row){
        var qtd=filtroCelula(row[4]);
        var custoUn=filtroCelula(row[5]);
        total+=(custoUn*qtd);
        
    });
    return total;
  }

  function writeCustoCompras(){
        var total= getTotalCompras();
        $("#custo_compras").html(valorFormatado(total));

  }

 

</script>

@endpush