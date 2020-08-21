@extends('produtos.master-edit')

@section('edit-content')

{!! Form::model($produto,['route'=>['produtos.update',$produto->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('produtos.form')
        @include('produtos.formcontrol')

     
{!! Form::close() !!}

@endsection

