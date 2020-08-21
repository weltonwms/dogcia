<input type="hidden" id="produtos_json" value="{{json_encode($produtos)}}">
<div class="form-row">
    <div class="col-md-4 ">
        <?php
            $dtmorte= isset($morte)  ? null : \Carbon\Carbon::now()->format('Y-m-d');
        ?>
        {{ Form::bsDate('data_morte', $dtmorte,['label'=>"Data Morte *"]) }}
    </div>

    <div class="col-md-12">
        {{ Form::bsSelect('produto_id',$produtosList,null,['label'=>"Produto", 'placeholder' => '--Selecione--','class'=>'select2']) }}

    </div>
    
    <div class="col-md-4">
        {{ Form::bsNumber('qtd',null,['label'=>"Qtd *",'min'=>'0']) }}
    </div>

    <div class="col-md-4">
        {{ Form::bsText('custo_medio',null,['label'=>"Custo Médio Un *", 'readonly'=>"readonly"]) }}
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="total" class="control-label">Total</label>
            <input type="text" id="total" class="form-control money" readonly>
        </div>
       
    </div>
    
    
</div>


@push('scripts')
    <script>
        var TelaMorte= (function(){
            var produtos=JSON.parse( $("#produtos_json").val() || '[]' );
            var currentProduto;
            var custo_medio_gravado;
            var qtd_gravada;
            var produto_id_gravado;

            function setCurrentProduto(id){
                currentProduto=getProduto(id);
            }

            function changeProduto(event){
                var value= event.currentTarget.value; //ou this.value
                setCurrentProduto(value);
                console.log(currentProduto);
                write();
            }

            function changeQtd(event){
                write();
            }

            function initOnEdit(){
                custo_medio_gravado= $("#custo_medio").val() || null;
                qtd_gravada= $("#qtd").val() || null;
                produto_id_gravado= $("#produto_id").val() || null;
               if(produto_id_gravado){
                    setCurrentProduto(produto_id_gravado);
                    console.log(currentProduto);
                    writeTotal();
               }
                
            }

            function getProduto(id){
                return produtos.find(function(el){
                    return el.id==id;
                });
            }

            function write(){
                writeCusto();
                writeTotal();
            }

            function writeCusto(){
                var custo_medio= getCustoMedio();
                $("#custo_medio").val(formatNumber(custo_medio));
            }

            function writeTotal(){
                var qtd= $("#qtd").val();
                var custo_medio= getCustoMedio();
                var total="";
                if(qtd && custo_medio){
                    total=qtd*custo_medio;
                }
                $("#total").val(formatNumber(total));
            }

            function getCustoMedio(){
                var custo_medio= currentProduto?currentProduto.custo_medio:"";
                var qtd= $("#qtd").val();
                if(produto_id_gravado && produto_id_gravado==currentProduto.id ){
                    var totalCustoAnterior=(qtd_gravada*custo_medio_gravado)
                    var totalCustoAdd= custo_medio*(qtd-qtd_gravada)
                    if(qtd>qtd_gravada){
                        custo_medio=(totalCustoAnterior+totalCustoAdd)/qtd;
                    }
                    else{
                        custo_medio= custo_medio_gravado;
                    }
                   
                }

                return custo_medio;
            }

            function formatNumber(n){
                var nr= parseFloat(n);
                if(!isNaN(nr)){
                    return nr.toFixed(2).toString().replace('.', ',');
                }
                return "";
            }

            return{
                changeProduto:changeProduto,
                changeQtd:changeQtd,
                initOnEdit:initOnEdit
            }

        })();

        

        

        $("#produto_id").change(TelaMorte.changeProduto);
        $("#qtd").change(TelaMorte.changeQtd);
        TelaMorte.initOnEdit();
    </script>
@endpush




