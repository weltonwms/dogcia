ProdutoVendaModel= (function Produtos() {
    var items = [];

    function addItem(item) {
        items.push(item);
    }

    function deleteItem(index) {
        var retorno= items.splice(index, 1);
        return retorno[0] //primeiro e único elemento removido.
    }

    function updateItem(item, index) {
        items.splice(index, 1, item);
    }

    function getItems() {
        return items;
    }

    function getItem(index){
        return items[index];
    }

    /*
     * Metodos de uma possivel outra classe. mexe com DOM
     */
    function inicializeItems() {
        var itemsJson = $("#produtos_json").val() || '[]';
        var itemsObj = JSON.parse(itemsJson);
        ItensGravados.setItems();
        items = itemsObj;
        updateTableProduto();
    }

    function getProdutoVenda(produto_id){
        var item= items.find(function(produto){
            return produto_id==produto.produto_id;
        });
        return item;
    }
    
    function getTotalGeral(){
        var map= items.map(function(item, i){
            return parseFloat(getTotalItem(i));
        });
        var soma= map.reduce(function(a,b){
           return a + b;
        },0)
        return soma;
    }
    
    function getTotalItem(index) {
        var item = items[index];
        if (item) {
            var total = parseInt(item.qtd) * parseFloat(item.valor_venda);
            return total.toFixed(2);
        }
    }
   

    function saveItem() {
        var id = $("#formProduto_id").val().trim();
        var produto_id = $("#formProduto_produto_id").val();
        var qtd = $("#formProduto_qtd").val();
        var valor_venda = ler_valor("#formProduto_valor_venda");
        var item = {produto_id: produto_id, qtd: qtd, valor_venda: valor_venda};
        if (id) {
            updateItem(item, id);
            $('#ModalFormProduto').modal('hide');
        } else {
            addItem(item);
        }

    }

    function editProduct(event) {
        event.preventDefault();
        var index = $(this).attr('editProduct');
        var item = items[index];
        TelaProduto.setIndex(index);
        TelaProduto.setCurrentProduto(item.produto_id);
        TelaProduto.setQtd(item.qtd);
        TelaProduto.setValorVenda(item.valor_venda);
        TelaProduto.writeForEdit();
        
        $('#ModalFormProduto').modal('show');

    }

    function deleteProduct(event) {
        event.preventDefault();
        var index = $(this).attr('deleteProduct');
        deleteItem(index);
        updateTableProduto();
    }

    function updateTableProduto() {
        var itemsString = JSON.stringify(items);
        $("#produtos_json").val(itemsString);
        var tableItems = items.map(function (item, i) {
            var product = getObjProduct(item.produto_id);
            return "<tr>" +
                    "<td>" + (i + 1) + "</td>" +
                    "<td>" + product.nome + "</td>" +
                    "<td>" + item.qtd + "</td>" +
                    "<td>" + valorFormatado(item.valor_venda) + "</td>" +
                    "<td>" + valorFormatado(getTotalItem(i)) + "</td>" +
                    '<td><a href="#" editProduct="' + i + '"> <i class="fa fa-edit"></i>  </a></td>' +
                    '<td><a href="#" deleteProduct="' + i + '"> <i class="fa fa-trash text-danger"></i>  </a></td>' +
                    "</tr>"
        });
        $("#tbodyTableProduto").html(tableItems);
        $("#total_geral_tabela").html(valorFormatado(getTotalGeral()));
        $("a[editProduct]").click(editProduct);
        $("a[deleteProduct]").click(deleteProduct);
        //console.log(tableItems);
    }

    

    return {
        addItem: addItem,
        deleteItem: deleteItem,
        updateItem: updateItem,
        getItems: getItems,
        getItem: getItem,
        updateTableProduto: updateTableProduto,
        saveItem: saveItem,
        inicializeItems: inicializeItems,
        getTotalItem: getTotalItem,
        getTotalGeral:getTotalGeral,
        getProdutoVenda:getProdutoVenda
    };

})();


TelaProduto=(function(){
    var currentProduto;
    var qtd=0;
    var valor_venda; //uso posterior para representar toda tela
    var index; //indice do item alugado já salvo na Lista. Usado para Edição

    function setCurrentProduto(produto_id){
        currentProduto=produto_id?getObjProduct(produto_id):null;
    }

    function getCurrentProduto(){
        return currentProduto;
    }

    function setQtd(valor){
        qtd=parseInt(valor) || 0;
    }

    function getQtd(){
        return qtd;
    }

    function getProdutoId(){
        if(currentProduto){
            return currentProduto.id;
        }
    }

    function getQtdDisponivelAtual(){
        if(!currentProduto){
            return null;
        }
        var qtdGravada= ItensGravados.getQtdGravadaByProduto(currentProduto.id); //qtdGravada Usada em Edição. Não desconta o que o próprio já tem gravado.
        var resultado= parseInt(currentProduto.qtd_disponivel) - qtd + qtdGravada;
                 
        return resultado;
    }

    function setValorVenda(valor){
        valor_venda=valor;
    }

    function setIndex(valor){
        index=valor;
    }

    function write(){
        $("#formProduto_qtd_disponivel").val(getQtdDisponivelAtual());
    }

    function writeForEdit(){
        $("#formProduto_produto_id").val(getProdutoId());
        $("#formProduto_qtd").val(qtd);
        $("#formProduto_qtd_disponivel").val(getQtdDisponivelAtual());
        $("#formProduto_valor_venda").val(valorFormatado(valor_venda));
        $("#formProduto_id").val(index);
        $('#formProduto_produto_id').trigger('change'); //avisar o select2 da mudança
    }

    function resetFormProduto() {
        currentProduto=null;
        qtd=null;
        index=null;
        valor_venda=null;
        $("#formProduto_id").val('');
        $("#formProduto_produto_id").val('');
        $("#formProduto_qtd").val('');
        $("#formProduto_valor_venda").val('');
        $("#formProduto_total").val('');
        $("#formProduto_qtd_disponivel").val('');
        $('#formProduto_produto_id').trigger('change'); //avisar o select2 da mudança
    }

    return {
        setCurrentProduto:setCurrentProduto,
        getCurrentProduto:getCurrentProduto,
        setQtd:setQtd,
        getQtd:getQtd,
        setValorVenda:setValorVenda,
        setIndex:setIndex,
        getProdutoId:getProdutoId,
        getQtdDisponivelAtual:getQtdDisponivelAtual,
        write:write,
        writeForEdit:writeForEdit,
        resetFormProduto:resetFormProduto
    };
})();
//Classe usada para saber o que já tem gravado no backend. Útil para cálculo de qtd Disponível
ItensGravados=(function(){
    var items=[];

    function setItems(){
        var valor= $('#itensGravados').val() || '[]';
        items = JSON.parse(valor);
        //Object.assign(items,itemsGravados);
    }

    function getQtdGravadaByProduto(produto_id){
        var obj=items.find(function(item){
            return item.produto_id==produto_id;
        });
        return obj?parseInt(obj.qtd):0;
    }

   
    return{
        setItems:setItems,
        getQtdGravadaByProduto:getQtdGravadaByProduto           
    }
})();

function getObjProduct(id) {
    var option = $('#formProduto_produto_id option[value=' + id + ']');
    if (option.val()) {
        var produtoJson = atob(option.attr('data-obj'));
        var produto = JSON.parse(produtoJson);
        return produto;
    }
}

function calculoTotal() {
    var qtd = ler_valor("#formProduto_qtd");
    var valor_venda = ler_valor("#formProduto_valor_venda");
   // console.log('qtd', qtd);
    //console.log('valor_venda', valor_venda); 
    if (qtd && valor_venda) {
        var total = qtd * valor_venda;
        
        $("#formProduto_total").val(valorFormatado(total));

    } else {
        $("#formProduto_total").val('');
    }
}

function valorFormatado(valorNumber){
    var v= Number.parseFloat(valorNumber); //garantindo que param vai ser number
    var valor_formatado = v.toFixed(2).toString().replace('.', ',');
    return valor_formatado; //string formatada
}

function ler_valor(campo) {
    var valor = $(campo).val().replace('.', '').replace(',', '.');
    return parseFloat(valor);
}

function checkErrors(){
    var qtd= TelaProduto.getQtd();
    var valor_venda= ler_valor("#formProduto_valor_venda");
    var produto_id= TelaProduto.getProdutoId();
    var errors=[];
    if(!produto_id){
        errors.push("Produto não Selecionado");
        return errors; // Não tem como prosseguir sem produto_id
    }
    var produto = getObjProduct(produto_id);
    if(!qtd || qtd < 1){
        errors.push("Quantidade Inválida");
    }
    if(!valor_venda){
        errors.push("Valor de Venda não Inserido");
    }
    
    if(TelaProduto.getQtdDisponivelAtual() < 0){
        errors.push("Qtd maior que Quantidade Disponível");
    }
    return errors;
}




ProdutoVendaModel.inicializeItems();
$("#btn_save_item").click(function () {
    var errors=checkErrors();
    if(errors.length===0){
        ProdutoVendaModel.saveItem();
        TelaProduto.resetFormProduto();
        ProdutoVendaModel.updateTableProduto();
        console.log(ProdutoVendaModel.getItems());
    }
    else{
        alert(errors.join('\n'));
    }
    
});

$('#ModalFormProduto').on('hidden.bs.modal', function (e) {
    TelaProduto.resetFormProduto();
});

$('#formProduto_produto_id').change(function (e) {
    TelaProduto.setCurrentProduto(this.value);
    if(!this.value){
        return false; //não é possível fazer nada se não tiver valor;
    }
    if(!isDuplicateProduct(this.value)){
        var produto = getObjProduct(this.value);
        $("#formProduto_valor_venda").val(valorFormatado(produto.valor_venda));
        TelaProduto.write();
        calculoTotal();
    }
    else{
        TelaProduto.setCurrentProduto('');
        $('#formProduto_produto_id').val(''); //analisar TelaProduto.write()
        alert('Produto já encontra-se na Lista');
    }
 
});


$("#formProduto_qtd, #formProduto_valor_venda ").on("change", function () {
    calculoTotal();
});

$("#formProduto_qtd").on("input", function (e) {
    TelaProduto.setQtd(this.value);
    TelaProduto.write();
});

function isDuplicateProduct(produto_id){
    var items= ProdutoVendaModel.getItems();
    var indexEncontrado= items.findIndex(function(item){
        return produto_id==item.produto_id;
    });
    var indexOriginal= parseInt($('#formProduto_id').val());
   
    //-1 indica que o produto não foi encontrado na lista
    // (indexEncontrado!=indexOriginal) é para liberar a edição
    if(indexEncontrado!=-1 && indexEncontrado!==indexOriginal){
       return true;
    }
    return false;
}