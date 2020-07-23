{{-- Usando o componentede Form bsText: forma mais curta que component blade FormGroup --}}
<div class="row">
    <div class="col-md-4">
        {{ Form::bsText('nome',null,['label'=>"Nome *"]) }}
    </div>

    <div class="col-md-8">
        {{ Form::bsText('descricao',null,['label'=>"Descrição"]) }}
    </div>

    <div class="col-md-4">
        {{ Form::bsNumber('qtd_estoque',null,['label'=>"Qtd Estoque *",'min'=>'0']) }}
    </div>

    <div class="col-md-4">
        {{ Form::bsText('valor_aluguel',null,['label'=>"Valor Aluguel *", 'class'=>"money"]) }}
    </div>

    <div class="col-md-4">
        {{ Form::bsText('valor_venda',null,['label'=>"Valor Venda *", 'class'=>"money"]) }}
    </div>

   


</div>