@extends('clientes.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'clientes.store','class'=>'','id'=>'adminForm'])!!}
                @include('clientes.form')

 {!! Form::close() !!}
@endsection