<div class="form-row">
    <div class="col-md-4">
        {{ Form::bsSelect('cliente_id',$clientes,null,['label'=>"Cliente *", 'placeholder' => '--Selecione--','class'=>'select2']) }}

    </div>
    <div class="col-md-4 ">
        <?php
            $dtVenda= isset($venda)  ? null : \Carbon\Carbon::now()->format('Y-m-d');
        ?>
        {{ Form::bsDate('data_venda',$dtVenda,['label'=>"Data Venda *"]) }}
    </div>
   
    <div class="col-md-4">
        {{ Form::bsText('observacao',null,['label'=>"Observação"]) }}
    </div>
</div>
{{ Form::hidden('produtos_json',null,['class'=>"form-control", 'id'=>'produtos_json']) }}
@error('produtos_json')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

@include('vendas.produto-venda')




