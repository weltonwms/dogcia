@extends('mortes.master-edit')

@section('edit-content')

{!! Form::model($morte,['route'=>['mortes.update',$morte->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}

@include('mortes.form')


{!! Form::close() !!}
@endsection