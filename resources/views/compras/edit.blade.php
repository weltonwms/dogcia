@extends('compras.master-edit')

@section('edit-content')

{!! Form::model($compra,['route'=>['compras.update',$compra->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}

@include('compras.form')


{!! Form::close() !!}
@endsection