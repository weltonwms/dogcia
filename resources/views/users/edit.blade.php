@extends('users.master-edit')

@section('edit-content')

{!! Form::model($user,['route'=>['users.update',$user->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('users.form')

       
{!! Form::close() !!}
@endsection



