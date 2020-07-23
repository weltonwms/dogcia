@extends('vendas.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'vendas.store','class'=>'','id'=>'adminForm'])!!}
                @include('vendas.form')

 {!! Form::close() !!}
@endsection