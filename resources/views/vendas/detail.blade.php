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
                <th>Custo Un</th>
                <th>Custo Total</th>
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
                <td>{{Util::moneyToBr(ProdutoHelper::custoMedio($produto),true)}}</td>
                <td>{{Util::moneyToBr(ProdutoHelper::custoMedioTotal($produto),true)}}</td>
                <td>{{$produto->pivot->getValorFormatado()}}</td>
                <td>{{$produto->pivot->getTotalFormatado()}}</td>
            </tr>
            @endforeach

            <tr>
                <td colspan="5"> </td>
                <td><b>Total Geral:</b></td>
                <td class="destaque1">{{Util::moneyToBr($venda->getTotalGeral(),true)}}</td>
            </tr>
        </tbody>
    </table>
</div>