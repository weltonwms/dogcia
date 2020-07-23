@extends('vendas.master-edit')

@section('edit-content')
<input type="hidden" id="itensGravados" value='<?php echo $venda->produtos_json ?>' >
{!! Form::model($venda,['route'=>['vendas.update',$venda->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('vendas.form')

       
{!! Form::close() !!}
@endsection



