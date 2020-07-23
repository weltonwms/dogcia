<div class="form-row">
    <div class="col-md-4">
        {{ Form::bsSelect('cliente_id',$clientes,null,['label'=>"Cliente", 'placeholder' => '--Selecione--','class'=>'select2']) }}

    </div>
    <div class="col-md-2 col-sm-6 ">
        <?php
            $dtSaida= isset($rent)  ? null : \Carbon\Carbon::now()->format('Y-m-d');
        ?>
        {{ Form::bsDate('data_saida', $dtSaida,['label'=>"Data Saída"]) }}
    </div>
    <div class="col-md-2 col-sm-6">
        {{ Form::bsDate('data_retorno',null,['label'=>"Data Retorno"]) }}
    </div>
    <div class="col-md-4">
        {{ Form::bsText('observacao',null,['label'=>"Observação"]) }}
    </div>
</div>
{{ Form::hidden('produtos_json',null,['class'=>"form-control", 'id'=>'produtos_json']) }}
@error('produtos_json')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

@include('rents.produto-rent')



