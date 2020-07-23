<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RelatorioRent;
use App\RelatorioProdutoRent;
use App\RelatorioVenda;
use App\RelatorioProdutoVenda;

class RelatorioController extends Controller
{
    public function rents(Request $request)
    {
        $relatorio = new RelatorioRent();
        $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;
        $dados = [
            'clientes' => \App\Cliente::pluck('nome', 'id'),
            'relatorio' => $result,
        ];
        return view("relatorios.rents", $dados);
    }

    public function produtoRent(Request $request)
    {
        $relatorio = new RelatorioProdutoRent();
        $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;
        $dados = [
            'clientes' => \App\Cliente::pluck('nome', 'id'),
            'produtos' => \App\Produto::pluck('nome', 'id'),
            'relatorio' => $result,
        ];
        return view("relatorios.produto-rent", $dados);
    }

    public function vendas(Request $request)
    {
        $relatorio = new RelatorioVenda();
        $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;
        $dados = [
            'clientes' => \App\Cliente::pluck('nome', 'id'),
            'relatorio' => $result,
        ];
        return view("relatorios.vendas", $dados);

    }

    public function produtoVenda(Request $request)
    {
        $relatorio = new RelatorioProdutoVenda();
        $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;
        $dados = [
            'clientes' => \App\Cliente::pluck('nome', 'id'),
            'produtos' => \App\Produto::pluck('nome', 'id'),
            'relatorio' => $result,
        ];
        return view("relatorios.produto-venda", $dados);
    }
}
