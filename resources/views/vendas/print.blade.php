<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impressão Venda - {{$venda->id}}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/paper.css') }}">
    <style>
        @page {
            size: A4
        }

        body {
            font-size: 14px;
        }

        .box {
            display: flex;

        }

        .box .column {
            flex: 1;
        }

        .box .column.f4 {
            flex: 4;

        }

        .text-right {
            text-align: right;
        }


        table {
            width: 100%;
            font-size: 13px;

        }

        thead tr th {
            border-top: 2px solid #aaa;
            border-bottom: 2px solid #aaa;
            text-align: left;
        }

        tfoot tr td {
            border-top: 2px solid #aaa;

        }

        .destaque {
            background-color: #E5E5DB;
        }

        #info_cliente .column {
            padding: 2px 0;
        }

        #info_cliente {
            margin: 20px 4px 30px 4px;
            color:#333;
        }

        #info_cliente .label{
            display: inline-block;
            min-width: 70px;
            font-weight: 600;
            
        }
    </style>

</head>

<body class="A4">

    <section class="sheet padding-10mm">
        <div class="box">
            <div class="column f4">
                <img src="{{ asset('img/logo_impressao.png') }}" alt="logo">
            </div>

            <div class="column text-right">
                <div>Código Venda:</div>
                <div>Data Venda: </div>
                
            </div>

            <div class="column text-right">
                <div> {{$venda->id}}</div>
                <div>{{$venda->data_venda}}</div>
               
            </div>

        </div>

        <hr>

        <div id="info_cliente">

            <div class="box">
                <div class="column"><span class="label">Cliente:  </span> {{$venda->cliente->nome}}</div>
                <div class="column"><span class="label">Telefone: </span> {{$venda->cliente->telefone}}</div>

            </div>

            <div class="box">
                <div class=" column"><span class="label">Endereço:</span> {{$venda->cliente->endereco}} </div>

            </div>

        </div>


        <table class="" cellspacing='0' cellpadding='1'>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="5%">Qtd</th>
                    <th width="60%">Discriminação</th>
                    <th width="15%">Preço Un</th>
                    <th width="15%">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($venda->produtos as $key=>$produto)
                <tr>
                   <td>{{++$key}}</td>
                   <td>{{$produto->pivot->qtd}}</td>
                   <td>{{$produto->nome}} - {{$produto->descricao}}</td>
                   <td>{{$produto->pivot->getValorFormatado()}}</td>
                   <td>{{$produto->pivot->getTotalFormatado()}}</td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3"> </td>
                    <td><b>Total Geral</b></td>
                    <td class="destaque"><b>{{$venda->getTotalGeralFormatado()}}</b></td>
                </tr>
            </tfoot>
        </table>
        <br><br>
        @if($venda->observacao)
            Obs: {{$venda->observacao}}
        @endif
    </section>

    <script>
        window.onload=function(){
            window.print();
        }
    </script>

    
</body>

</html>