{{-- Campos de Controle do Formulário de Produto --}}

<div class="card  bg-light ">
    <div class="card-body">

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Qtd Estoque</label>
                <input readonly="readonly" type="text" class="form-control" value="{{Util::floatBr($produto->qtd_estoque) }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Custo Médio Un</label>
                    <input readonly="readonly" id="custo_medio" type="text" class="form-control" value="{{Util::moneytoBr($produto->custo_medio )}}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Valor Venda</label>
                    <input readonly="readonly" id="valor_venda" type="text" class="form-control" value="{{Util::moneyToBr($produto->getValorVenda()) }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Granel</label>
                    <input readonly="readonly" type="text" class="form-control" value="{{Util::floatBr($produto->granel) }}">
                </div>
            </div>
        </div>


    </div>
</div>

@push("scripts")
<script>
    function changeMargem(){
        var margem= lerInputNumber("#margem");
        var custo_medio= ler_valor("#custo_medio");
        var valor_venda= custo_medio*(margem/100 +1);
        $("#valor_venda").val(valorFormatado(valor_venda));
        console.log('margem',margem);
        console.log('custo',custo_medio);
    }
    $("#margem").change(changeMargem);
</script>
@endpush