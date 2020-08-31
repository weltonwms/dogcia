@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>'Mortes', 'icon'=>'fa-bed','route'=>route('mortes.index'),'subtitle'=>'Gerenciamento de
Mortes'])

@endbreadcrumbs
@endsection

@section('toolbar')
@toolbar

<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('mortes.create')}}">
    <i class="fa fa-plus-circle"></i>Novo
</a>


<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link"
    data-route="{{url('mortes/{id}/edit')}}" onclick="dataTableSubmit(event)">
    <i class="fa fa-pencil"></i>Editar
</button>

<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete"
    data-route="{{route('mortes_bath.destroy')}}" onclick="dataTableSubmit(event)">
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
        <th>Data Morte</th>
        <th>Qtd</th>
        <th>Custo Médio Un</th>
        <th>Prejuízo</th>
        <th id>ID</th>
    </tr>
</thead>

<tbody>
    @foreach($mortes as $morte)
    <tr>

        <td></td>
        <td>
          <a href="{{route('mortes.edit', $morte->id)}}">
            {{$morte->produto->getNomeCompleto()}}
        </a>
        </td>
        
        <td>{{$morte->data_morte}}</td>
        <td>{{$morte->qtd}}</td>
        <td>{{Util::moneyToBr($morte->custo_medio, true)}}</td>
        <td>{{Util::moneyToBr($morte->custo_medio*$morte->qtd, true)}}</td>
        <td>{{$morte->id}}</td>
    </tr>
    @endforeach
</tbody>
@enddatatables


@endsection

