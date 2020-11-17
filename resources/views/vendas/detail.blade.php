<hr class="mt-0">
<div class="row">
    <div class="col-md-8">
        <div><b class="label1">Cód Venda</b>: {{$venda->id}}</div>
    </div>

    <div class="col-md-4">
        <div><b class="label1">Status</b>: {{$venda->statusNome()}}</div>
    </div>

    <div class="col-md-8">
        <div><b class="label1">Data de Venda</b>: {{$venda->data_venda}}</div>
    </div>

    <div class="col-md-4">
        <div><b class="label1">Frete</b>: {{$venda->isFreteNome()}}</div>
    </div>

    <div class="col-md-8">
        <div><b class="label1">Cliente</b>: {{$venda->cliente->nome}}</div>
    </div>

    <div class="col-md-4">
        <div><b class="label1">Carteira</b>: {{$venda->isCarteiraNome()}}</div>
    </div>

    <div class="col-md-8">
        <div><b class="label1">Vendedor</b>: {{$venda->seller->nome}}</div>
    </div>

    <div class="col-md-4">
        <div><b class="label1">Pagamento</b>: {{$venda->formaPagamentoNome()}}</div>
    </div>

   
    @if($venda->observacao)
    <div class="col-md-12">
        <div><b class="label1">Observação</b>: {{$venda->observacao}}</div>
    </div>
    @endif
</div>

<hr>
<br>
<br>
<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Qtd</th>
                <th>Discriminação</th>
                @if(auth()->user()->isDono)
                <th>Custo Un</th>
                <th>Custo Total</th>
                @endif
                <th>Preço Un</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>

            @foreach($venda->produtos as $key=>$produto)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$produto->pivot->qtd}}</td>
                <td>{{ProdutoHelper::descricao($produto)}}</td>
                @if(auth()->user()->isDono)
                <td>{{Util::moneyToBr(ProdutoHelper::custoMedio($produto),true)}}</td>
                <td>{{Util::moneyToBr(ProdutoHelper::custoMedioTotal($produto),true)}}</td>
                @endif
                <td>{{$produto->pivot->getValorFormatado()}}</td>
                <td>{{$produto->pivot->getTotalFormatado()}}</td>
            </tr>
            @endforeach

            <?php $colspan=auth()->user()->isDono?"5":"3";?>

            @if($venda->frete)
            <tr>
                <td colspan="{{$colspan}}"> </td>
                <td><b>SubTotal:</b></td>
                <td class="">{{Util::moneyToBr($venda->getSubtotal(),true)}}</td>
            </tr>
           
            <tr>
                <td colspan="{{$colspan}}"> </td>
                <td><b>Frete:</b></td>
                <td class="">{{Util::moneyToBr($venda->valor_frete,true)}}</td>
            </tr>
            @endif
            <tr>
                <td colspan="{{$colspan}}"> </td>
                <td><b>Total Geral:</b></td>
                <td class="destaque1">{{Util::moneyToBr($venda->getTotalGeral(),true)}}</td>
            </tr>
        </tbody>
    </table>
</div>