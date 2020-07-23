@extends('produtos.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'produtos.store','class'=>'','id'=>'adminForm'])!!}
                @include('produtos.form')

 {!! Form::close() !!}
@endsection