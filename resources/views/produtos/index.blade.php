@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>'Produtos', 'icon'=>'fa-gift','route'=>route('produtos.index'),'subtitle'=>'Gerenciamento de Produtos'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('produtos.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('produtos/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('produtos_granel/{id}')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-balance-scale"></i>Granel</button>

<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('produtos_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')

<div class="tile tile-nomargin">
    <form action="{{route('produtos.index')}}">
        <label class="text-primary">Ser Vivo</label>
        {!!Form::select('vivo', [""=>"Todos",'1' => 'Sim', '0' => 'Não'],
        request('vivo'),
        ['onchange'=>"this.form.submit()"]
        )!!}
        &nbsp;&nbsp;
        <label class="text-primary">Estoque</label>
        {!!Form::select('estoque', [''=>"Todos",'1'=>">=1",'0' => 'Em Falta'],
        request('estoque'),
        ['onchange'=>"this.form.submit()"]
        )!!}
         &nbsp;&nbsp;
         <label class="text-primary">Custo Estoque</label>
        <button type="button" class="btn btn-outline-info btn-sm">R$ <span id="custo_estoque">0,00</span></button>

    </form>
</div>

@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th>Nome</th>
        <th>Ser Vivo</th>
        <th>Qtd Estoque</th>
        <th>Custo Médio Un</th>
        <th>Margem %</th>
        <th>Valor Venda</th>
        <th>Granel</th>
        <th>Descrição</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($produtos as $produto)
    <tr>
       
        <td></td>
        <td><a href="{{route('produtos.edit', $produto->id)}}">{{$produto->getNomeCompleto()}}</a></td>
        <td>{{$produto->ser_vivo_texto}}</td>
        
        <td>{{$produto->qtd_estoque}}</td>
        <td>{{Util::moneyToBr($produto->custo_medio,true)}}</td>
        <td>{{$produto->margem}}</td>
        <td>{{Util::moneyToBr($produto->getValorVenda(),true)}}</td>
        <td>{{Util::floatBr($produto->granel)}}</td>
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
    var tabela= Tabela.getInstance({colId:9}); //instanciando dataTable e informando a coluna do id
    writeCustoEstoque();
    tabela.on( 'search.dt', function () {
        writeCustoEstoque();
    } );

});
  //fim start Datatable//

  function getTotalCusto(){
    function filtroCelula(celula){
        return parseFloat(celula.replace("R$", "").trim().replace(".","").replace(",","."));
    }
    var tabela= Tabela.getInstance();
    var total=0;
    tabela.rows({filter:'applied'}).data().each(function(row){
        var custo=filtroCelula(row[4]);
        var qtd=filtroCelula(row[3]);
        total+=(custo*qtd);
        
    });
    return total;
  }

  function writeCustoEstoque(){
        var total= getTotalCusto();
        $("#custo_estoque").html(valorFormatado(total));

  }



</script>
@endpush