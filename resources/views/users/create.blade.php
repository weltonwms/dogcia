@extends('users.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'users.store','class'=>'','id'=>'adminForm'])!!}
                @include('users.form')

 {!! Form::close() !!}
@endsection