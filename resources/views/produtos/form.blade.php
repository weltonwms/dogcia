<?php
$grandezas=[''=>"-Selecione-",1=>"Kilo",2=>"Litro",3=>"Unitário"];

?>



  
  
  

<div class="row">
    <div class="col-md-6">
        {{ Form::bsText('nome',null,['label'=>"Nome *"]) }}
    </div>

    <div class="col-md-6">
        
        <div class="form-group groupSerVivo">
            <label for="" class="control-label yesno">Ser Vivo</label>
            {{ Form::bsYesno('ser_vivo','0') }} 
          </div>
 
       
       
    </div>


    <div class="col-md-6">
        {{ Form::bsSelect('grandeza',$grandezas,null,['label'=>"Grandeza *"]) }}

    </div>

    <div class="col-md-6 ">
        <div class="blValorGrandeza">
            {{ Form::bsNumber('valor_grandeza',null,['label'=>"Valor Grandeza *",'min'=>'1']) }}
        </div>
    </div>

    <div class="col-md-6">
        {{ Form::bsText('descricao',null,['label'=>"Descrição"]) }}
    </div>

    <div class="col-md-6">
        {{ Form::bsNumber('margem',null,['label'=>"Margem % *"]) }}
    </div>

  

</div>

@push('scripts')
<script>

    function visibleValorGrandeza(){
        var grandeza=$("[name=grandeza]").val();
        if(grandeza=="1" || grandeza=="2"){
            $(".blValorGrandeza").show();
            $( "[name=valor_grandeza]" ).prop( "disabled", false );
            
        }
        else{
            $(".blValorGrandeza").hide();
            $( "[name=valor_grandeza]" ).prop( "disabled", true );
           
        }
    }

    function setGrandeza(){
        var ser_vivo= $("[name=ser_vivo]:checked").val();
        if(ser_vivo=='1'){
           $("[name=grandeza]").val('3');
          
        }
        visibleValorGrandeza();
        
    }

    function setSerVivo(){
        var grandeza=$("[name=grandeza]").val();
        if(grandeza=="1" || grandeza=="2"){
            $('.groupSerVivo .no').button('toggle');
            
            
        }
        
        
    }

    $("[name=ser_vivo]").change(function(){
        setGrandeza();

        //console.log('valor do this: ',this);
       // alert('mudou');
    })

   

    $("[name=grandeza]").change(function(){
        visibleValorGrandeza();
        setSerVivo();
    });

    $("[name=grandeza]").trigger('change')
    
</script>

@endpush