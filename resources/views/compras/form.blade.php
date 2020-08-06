<div class="form-row">
    <div class="col-md-4">
        {{ Form::bsSelect('produto_id',$produtos,null,['label'=>"Produto", 'placeholder' => '--Selecione--','class'=>'select2']) }}

    </div>
    <div class="col-md-2 col-sm-6 ">
        <?php
            $dtCompra= isset($compra)  ? null : \Carbon\Carbon::now()->format('Y-m-d');
        ?>
        {{ Form::bsDate('data_compra', $dtCompra,['label'=>"Data Compra"]) }}
    </div>
    <div class="col-md-2 col-sm-6">
        {{ Form::bsDate('vencimento',null,['label'=>"Data Vencimento"]) }}
    </div>
    <div class="col-md-6">
        {{ Form::bsNumber('qtd',null,['label'=>"Qtd *",'min'=>'0']) }}
    </div>
    
    <div class="col-md-4">
        {{ Form::bsText('valor_compra',null,['label'=>"Valor Compra Un *", 'class'=>"money"]) }}
    </div>
</div>




