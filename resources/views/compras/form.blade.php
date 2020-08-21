<div class="form-row">
    <div class="col-md-4">
        
                @formgroup(['label'=>"Produto *"])
                <div class="input-group ">
                    {{ Form::select('produto_id',$produtos,null,['id'=>"produto_id", 'placeholder' => '--Selecione--','class'=>'select2 form-control','style'=>"width:88%"]) }}

                   
                    <div class="input-group-append">
                       
                      <button class="btn btn-outline-primary btn-sm addProduto" 
                        data-toggle="modal"
                        data-target="#ModalFormProduto"
                        type="button" title="Criar Produto Caso não Exista">
                         <i class="fa fa-plus"></i>
                        </button>
                    </div>
                  </div>
                @endformgroup
    
    </div>

    <div class="col-md-4 ">
        <?php
            $dtCompra= isset($compra)  ? null : \Carbon\Carbon::now()->format('Y-m-d');
        ?>
        {{ Form::bsDate('data_compra', $dtCompra,['label'=>"Data Compra *"]) }}
    </div>
    <div class="col-md-4">
        {{ Form::bsDate('vencimento',null,['label'=>"Data Vencimento"]) }}
    </div>
    <div class="col-md-4">
        {{ Form::bsNumber('qtd',null,['label'=>"Qtd *",'min'=>'0']) }}
    </div>
    
    <div class="col-md-4">
        {{ Form::bsText('valor_compra',null,['label'=>"Valor Compra Un *", 'class'=>"money"]) }}
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="total" class="control-label">Total</label>
            <input type="text" id="total" class="form-control money">
        </div>
       
    </div>

</div>








@push('scripts')
    <script>
        function setTotal(){
            var qtd=lerValor("input[name=qtd]");
            var valor=lerValor("input[name=valor_compra]");
            if(qtd && valor){
                var total=qtd*valor;
                escrever(total,"#total");
            }
            
        }

        function setValor(){
            var qtd=lerValor("input[name=qtd]");
            var total=lerValor("#total");
            if(qtd && total){
                var valor=total/qtd;
                escrever(valor,"input[name=valor_compra]");
            }
            
        }

        function escrever(valor, campo){
            if(isNaN(valor)){
                return false;
            }
            var valor_formatado = valor.toFixed(2).toString().replace('.', ',');
            $(campo).val(valor_formatado);
        }

        function lerValor(campo) {
            var valor = $(campo).val().replace('.', '').replace(',', '.');
            return parseFloat(valor);
        }

        $("input[name=qtd], input[name=valor_compra]").change(setTotal);
        $("#total").change(setValor);

     

    </script>



@endpush


