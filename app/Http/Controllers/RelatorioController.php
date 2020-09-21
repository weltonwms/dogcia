<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RelatorioVenda;
use App\RelatorioProdutoVenda;
use App\RelatorioProduto;
use App\RelatorioSeller;

class RelatorioController extends Controller
{
    public function vendas(Request $request)
    {
        $relatorio = new RelatorioVenda();
        $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;
        $dados = [
            'clientes' => \App\Cliente::pluck('nome', 'id'),
            'sellers' => \App\Seller::pluck('nome', 'id'),
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
            'sellers' => \App\Seller::pluck('nome', 'id'),
            'produtos' => \App\Produto::getList(),
            'relatorio' => $result,
        ];
        return view("relatorios.produto-venda", $dados);
    }

    public function produtos(Request $request)
    {
        $relatorio = new RelatorioProduto();
        $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;
        $dados = [
            'produtos' => \App\Produto::getList(),
            'relatorio' => $result,
        ];
        return view("relatorios.produtos", $dados);
    }

    public function sellers(Request $request)
    {
        $relatorio = new RelatorioSeller();
        
        $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;
        
        $dados = [
            'sellers' => \App\Seller::pluck('nome', 'id'),
            'relatorio' => $result,
        ];
        return view("relatorios.sellers", $dados);
    }

    
}
