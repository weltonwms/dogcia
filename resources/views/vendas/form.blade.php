<div class="form-row">
    <div class="col-md-3">
        {{ Form::bsSelect('cliente_id',$clientes,null,['label'=>"Cliente *", 'class'=>'select2']) }}

    </div>
    <div class="col-md-3 ">
        <?php
            $dtVenda= isset($venda)  ? null : \Carbon\Carbon::now()->format('Y-m-d');
        ?>
        {{ Form::bsDate('data_venda',$dtVenda,['label'=>"Data Venda *"]) }}
    </div>

    <div class="col-md-3">
        <div class="form-group groupSerVivo">
            <label for="" class="control-label yesno">Frete *</label>
            {{ Form::bsYesno('frete','0') }} 
          </div>

    </div>

    <div class="col-md-3">
        <div class="form-group groupSerVivo">
            <label for="" class="control-label yesno">Carteira *</label>
            {{ Form::bsYesno('carteira','0') }} 
          </div>

    </div>
   
    <div class="col-md-3">
        {{ Form::bsText('observacao',null,['label'=>"Observação"]) }}
    </div>

    <div class="col-md-3">
        {{ Form::bsSelect('seller_id',$sellers,null,['label'=>"Vendedor *", 'placeholder' => '--Selecione--','class'=>'select2']) }}

    </div>

   

    <div class="col-md-3">
        {{ Form::bsSelect('status',['1'=>"Pago", '2'=>"Não Pago"],null,['label'=>"Status *", 'class'=>'select2']) }}

    </div>

    <div class="col-md-3">
        {{ Form::bsSelect('forma_pagamento',['1'=>"Dinheiro", '2'=>"Cartão Crédito", "3"=>"Cartão Débito"],null,['label'=>"Forma Pagamento *", 'placeholder' => '--Selecione--','class'=>'select2']) }}

    </div>

    
</div>
{{ Form::hidden('produtos_json',null,['class'=>"form-control", 'id'=>'produtos_json']) }}
@error('produtos_json')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

@include('vendas.produto-venda')

@push("scripts")
<script>
    
    function changeFreteCarteira(event){
        var value= this.value;
        if(value==1){
            $("#status").val(2);
            $('#status').trigger('change'); //avisar o select2 da mudança
        }
    }

    $("[name=frete], [name=carteira]").change(changeFreteCarteira);
</script>
@endpush




