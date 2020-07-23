<?php

namespace App\Helpers;

//use App\Rent;
use App\Produto;
use App\ProdutoVenda;

/**
 * Classe de apoio a Produtos
 *
 * @author welton
 */
class ProdutoHelper
{

    public static function getProdutosByRentsIds($rents_ids)
    {
        $produtos = Produto::whereHas('rents', function ($query) use ($rents_ids) {
            $query->whereIn('rent_id', $rents_ids);
        })->get();
        return $produtos;
    }

    public static function updateQtdDisponivelByProdutoId($produto_id)
    {
        $produto = Produto::find($produto_id);
        $produto->updateQtdDisponivel();
    }

    public static function updateQtdDisponivelByProdutos($produtos)
    {
        foreach ($produtos as $produto):
            $produto->updateQtdDisponivel();
        endforeach;
    }

    public static function getProdutosVendaByVendasIds($vendas_ids)
    {
        $produtosVenda = ProdutoVenda::whereIn('venda_id', $vendas_ids)->get();
        return $produtosVenda;
    }
    /**
     * Método que Repoõe as Qtd de estoque e Qtd Disponivel em um Produto ao ser
     * apagado de vendas. Útil para operações em lote, pois em lote Eventos não
     * são acionados
     * @param array $produtosVenda. Array de \App\ProdutoVenda.
     * @return int $affect. Nº de registros alterados.
     */
    public static function updateQtdProdutoOnDeleteVendas($produtosVenda)
    {
        $affect = \DB::transaction(function () use ($produtosVenda) {
            $afect = 0;
            foreach ($produtosVenda as $produto):
                $afect += \DB::update('update produtos
		                set qtd_estoque = qtd_estoque + ?,
		                qtd_disponivel = qtd_disponivel + ?
		                where id = ?',
                    [$produto->qtd, $produto->qtd, $produto->produto_id]);

            endforeach;

            return $afect;

        });
        return $affect;

    }

}
