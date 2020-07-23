@extends('clientes.master-edit')

@section('edit-content')

{!! Form::model($cliente,['route'=>['clientes.update',$cliente->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('clientes.form')


{!! Form::close() !!}
@endsection