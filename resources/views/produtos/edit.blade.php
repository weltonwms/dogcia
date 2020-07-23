@extends('produtos.master-edit')

@section('edit-content')
<h5 class="text-center">Qtd Fora (alugados): <span class="badge badge-primary count_rents">{{$produto->countRents()}}</span></h5>
<h5 class="text-center">Qtd Disponível: <span class="badge badge-primary qtd_disponivel">{{$produto->qtd_disponivel}}</span></h5>
{!! Form::model($produto,['route'=>['produtos.update',$produto->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('produtos.form')

       
{!! Form::close() !!}

@endsection

@push('scripts')
<script>
$('#qtd_estoque').change(function(){
        var estoque= parseInt(this.value);
        var countRents= parseInt($(".count_rents").text());
        var qtd_disponivel= estoque - countRents;
        $('.qtd_disponivel').text(qtd_disponivel);
});

</script>

@endpush