<hr class="mt-0">
<div class="row">
    <div class="col-md-8">
        <div><b class="label1">Cód Aluguel</b>: {{$rent->id}}</div>
    </div>

    <div class="col-md-4">
        <div><b class="label1">Status</b>: {!!$rent->getNomeStatus()!!}</div>
    </div>

    <div class="col-md-8">
        <div><b class="label1">Data de Saída</b>: {{$rent->data_saida}}</div>
    </div>

    <div class="col-md-4">
        <div><b class="label1">Data de Retorno</b>: {{$rent->data_retorno}}</div>
    </div>

    <div class="col-md-12">
        <div><b class="label1">Cliente</b>: {{$rent->cliente->nome}}</div>
    </div>
    @if($rent->observacao)
    <div class="col-md-12">
        <div><b class="label1">Observação</b>: {{$rent->observacao}}</div>
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
                <th>Preço Un</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>

            @foreach($rent->produtos as $key=>$produto)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$produto->pivot->qtd}}</td>
                <td>{{$produto->nome}} - {{$produto->descricao}}</td>
                <td>{{$produto->pivot->getValorFormatado()}}</td>
                <td>{{$produto->pivot->getTotalFormatado()}}</td>
            </tr>
            @endforeach

            <tr>
                <td colspan="3"> </td>
                <td><b>Total Geral:</b></td>
                <td class="destaque1">{{$rent->getTotalGeralFormatado()}}</td>
            </tr>
        </tbody>
    </table>
</div>