@extends('sellers.master-edit')

@section('edit-content')

{!! Form::model($seller,['route'=>['sellers.update',$seller->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('sellers.form')


{!! Form::close() !!}
@endsection