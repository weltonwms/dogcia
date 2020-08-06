@extends('mortes.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'mortes.store','class'=>'','id'=>'adminForm'])!!}
                @include('mortes.form')

 {!! Form::close() !!}
@endsection