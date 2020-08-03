@extends('sellers.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'sellers.store','class'=>'','id'=>'adminForm'])!!}
                @include('sellers.form')

 {!! Form::close() !!}
@endsection