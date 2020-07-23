@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>'Usuários', 'icon'=>'fa-user-secret','route'=>route('users.index'),'subtitle'=>'Gerenciamento de Usuários'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('users.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('users/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('users_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th>Nome de Usuário</th>
        <th>Nome</th>
        <th>Email</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($users as $user)
    <tr>
       
        <td></td>
        <td><a href="{{route('users.edit', $user->id)}}">{{$user->username}}</a></td>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->id}}</td>
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
    Tabela.getInstance({colId:4}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
</script>
@endpush