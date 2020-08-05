@extends('compras.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'compras.store','class'=>'','id'=>'adminForm'])!!}
                @include('compras.form')

 {!! Form::close() !!}
@endsection