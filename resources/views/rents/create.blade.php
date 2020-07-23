@extends('rents.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'rents.store','class'=>'','id'=>'adminForm'])!!}
                @include('rents.form')

 {!! Form::close() !!}
@endsection