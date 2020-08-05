<?php
$grandezas=[''=>"-Selecione-",1=>"Kilo",2=>"Litro",3=>"Unitário"];
$seres=[0=>"Não",1=>"Sim"];
?>
<div class="row">
    <div class="col-md-6">
        {{ Form::bsText('nome',null,['label'=>"Nome *"]) }}
    </div>

    <div class="col-md-6">
        {{ Form::bsSelect('ser_vivo',$seres,null,['label'=>"Ser Vivo *"]) }}

    </div>


    <div class="col-md-6">
        {{ Form::bsSelect('grandeza',$grandezas,null,['label'=>"Grandeza *"]) }}

    </div>

    <div class="col-md-6">
        {{ Form::bsNumber('valor_grandeza',null,['label'=>"Valor Grandeza *",'min'=>'1']) }}
    </div>

    <div class="col-md-6">
        {{ Form::bsText('descricao',null,['label'=>"Descrição"]) }}
    </div>

    <div class="col-md-6">
        {{ Form::bsNumber('margem',null,['label'=>"Margem %"]) }}
    </div>

    <div class="toggle lg">
        <label>
            Ser Vivo 
            <input type="checkbox"><span class="button-indecator"></span>
        </label>
    </div>






</div>