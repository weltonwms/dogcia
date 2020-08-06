<div class="form-row">
    <div class="col-md-12">
        {{ Form::bsSelect('produto_id',$produtos,null,['label'=>"Produto", 'placeholder' => '--Selecione--','class'=>'select2']) }}

    </div>
    
    <div class="col-md-12">
        {{ Form::bsNumber('qtd',null,['label'=>"Qtd *",'min'=>'0']) }}
    </div>
    
    
</div>




