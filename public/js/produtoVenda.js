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
        items = JSON.parse(itemsJson);
        //iniciar para edição
        ItensGravados.setItems();
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
            var total = parseFloat(item.qtd) * parseFloat(item.valor_venda);
            return total.toFixed(2);
        }
    }
   

    function saveItem() {
        var id = $("#formProduto_id").val().trim();
        var produto_id = $("#formProduto_produto_id").val();
        var qtd = lerInputNumber("#formProduto_qtd");
        var valor_venda = ler_valor("#formProduto_valor_venda");
        var custo_medio=ler_valor("#formProduto_custo_medio");
        var granel=$("#formProduto_granel").is(":checked")?1:0;
        
        var item = {produto_id: produto_id, qtd: qtd, valor_venda: valor_venda,custo_medio:custo_medio, granel:granel};
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
        
        //TelaProduto.setCustoMedio(item.custo_medio);
       // TelaProduto.setValorVenda(item.valor_venda);
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
                    "<td>" + getDescricao(item,product) + "</td>" +
                    "<td>" + floatBr(item.qtd) + "</td>" +
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
    var produtoVendaModel; //model relacionado. Produto já salvo na lista. Usado em Edição
    var qtd=0;
    var index; //indice do item vendido já salvo na Lista. Usado para Edição
    var isGranel=false;
    //atributos abaixo não são usados devido a suposições de valores de currentProduto ou produtoVendaModel
    var valor_venda; 
    var margem;
    var custo_medio;
   
    function inicialize(){
        //Espécie de Construtor do Modal. Colocar ações pararelas ao mostrar tela.
        showBlocoCm();
        
    }

   

    function setCurrentProduto(produto_id){
        currentProduto=produto_id?getObjProduct(produto_id):null;
        //ao mudar o currentProduto, setar o model relacionado a esse produto se houver
        produtoVendaModel=ProdutoVendaModel.getProdutoVenda(produto_id);
    }

    function getCurrentProduto(){
        return currentProduto;
    }

    function setQtd(valor){
        qtd=parseFloat(valor) || 0;
    }

    function getQtd(){
        return qtd;
    }

    function getProdutoId(){
        if(currentProduto){
            return currentProduto.id;
        }
    }

    function getValorVenda(){
        if(produtoVendaModel){
            return produtoVendaModel.valor_venda;
        }
        if(!currentProduto){
            return; //não tem como ter valor sem CurrentProduto
        }
        if(isGranel){
            return currentProduto.valor_venda_granel;
        }
        return currentProduto.valor_venda;
    }

    function getCustoMedio(){
        if(produtoVendaModel){
            return produtoVendaModel.custo_medio;
        }
        return currentProduto.custo_medio;
    }

    function getQtdDisponivelAtual(){
        if(!currentProduto){
            return null;
        }
       
        var qtdGravada= ItensGravados.getQtdGravadaByProduto(currentProduto.id); //qtdGravada Usada em Edição. Não desconta o que o próprio já tem gravado.
      
        var nomeAttr=isGranel?"granel":"qtd_estoque"; //se considera qtd a granel ou normal
        var resultado= parseFloat(currentProduto[nomeAttr]) - qtd + qtdGravada;
        
        return resultado;
    }

    

    function setIndex(valor){
        index=valor;
    }

    function showBlocoCm(){
        if(isGranel){
            $(".bloco_cm").hide();
            $(".bloco_cm_granel").show();
        }
        else{
            $(".bloco_cm_granel").hide();
            $(".bloco_cm").show();
        }
    }

    function showInfoGrandeza(){
        if(isGranel){
            $(".info_grandeza").show();
        }
        else{
            $(".info_grandeza").hide();
            
        }
        if(!currentProduto){
            return; //Não dá para saber a grandeza;
        }
        var siglas=['',"Kg","Lt","Un"];
        var siglaGrandeza= siglas[currentProduto.grandeza];
        $(".valor_grandeza").html(siglaGrandeza);
       
    }

    function writeCustoMedio(){
        $("#formProduto_custo_medio").val(valorFormatado(getCustoMedio()));
        var custoGranel= 0;
        if(currentProduto && currentProduto.valor_grandeza){
            custoGranel= getCustoMedio()/currentProduto.valor_grandeza;
        }
        
        $("#formProduto_custo_medio_granel").val(valorFormatado(custoGranel));
    }

    function writeValorVenda(){
        $("#formProduto_valor_venda").val(valorFormatado(getValorVenda()));
    }

    function write(){
      
       showBlocoCm();
       showInfoGrandeza()
       writeCustoMedio();
       writeValorVenda();
        $("#formProduto_qtd_estoque").val(getQtdDisponivelAtual());
        $("#formProduto_margem").val(currentProduto.margem);
    }

    function writeForEdit(){
        
        $("#formProduto_id").val(index);
        $("#formProduto_produto_id").val(getProdutoId());
       
        $("#formProduto_qtd").val(qtd);
         isGranel=produtoVendaModel.granel==1;
        $("#formProduto_granel").prop("checked", isGranel);
        //avisar o select2 da mudança ; chamada implicita de write()
        $('#formProduto_produto_id').trigger('change'); 

    }


    function onChangeQtd(event){
        setQtd(this.value);
        $("#formProduto_qtd_estoque").val(getQtdDisponivelAtual());
    }

    function onChangeGranel(event){
        //setGranel(this.value);
        isGranel= $(this).is(":checked");
        showBlocoCm();
        showInfoGrandeza()
        writeValorVenda();
              
        $("#formProduto_qtd_estoque").val(getQtdDisponivelAtual());
    }

    function onChangeProduto(event) {
        setCurrentProduto(this.value);
        if (!this.value)
        {
            return false; //não é possível fazer nada se não tiver valor;
        }
        if (!isDuplicateProduct(this.value))
        {
            write();
            calculoTotal();
        }
        else
        {
            setCurrentProduto('');
            $('#formProduto_produto_id').val(''); //analisar TelaProduto.write()
            alert('Produto já encontra-se na Lista');
        }
    }

    function resetFormProduto() {
        currentProduto=null;
        produtoVendaModel=null;
        qtd=null;
        index=null;
        isGranel=false;
        //valor_venda=null;
        $("#formProduto_id").val('');
        $("#formProduto_produto_id").val('');
        $("#formProduto_qtd").val('');
        $("#formProduto_valor_venda").val('');
        $("#formProduto_total").val('');
        $("#formProduto_qtd_estoque").val('');
        $("#formProduto_custo_medio").val('');
        $("#formProduto_custo_medio_granel").val('');
        
        $("#formProduto_granel").prop("checked", false);
        $("#formProduto_margem").val('');
        $('#formProduto_produto_id').trigger('change'); //avisar o select2 da mudança
    }

    return {
        inicialize:inicialize,
        setCurrentProduto:setCurrentProduto,
        getCurrentProduto:getCurrentProduto,
        setQtd:setQtd,
        getQtd:getQtd,
       // setValorVenda:setValorVenda,
        setIndex:setIndex,
        getProdutoId:getProdutoId,
        getQtdDisponivelAtual:getQtdDisponivelAtual,
        write:write,
        writeForEdit:writeForEdit,
        resetFormProduto:resetFormProduto,
        onChangeQtd:onChangeQtd,
        onChangeProduto:onChangeProduto,
        onChangeGranel:onChangeGranel
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
        return obj?parseFloat(obj.qtd):0;
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
    var qtd = lerInputNumber("#formProduto_qtd");
    var valor_venda = ler_valor("#formProduto_valor_venda");
  
    if (qtd && valor_venda) {
        var total = qtd * valor_venda;
        $("#formProduto_total").val(valorFormatado(total));

    } else {
        $("#formProduto_total").val('');
    }
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
    //var produto = getObjProduct(produto_id);
    if(!qtd || qtd <= 0){
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

function getDescricao(produtoVendido, produto){
    var nomes=['','Kg','Lt','Un'];
    var descricao;
    if(produtoVendido.granel){
        descricao=produto.nome+' (Granel '+nomes[produto.grandeza]+') ';
    }
    else{
        descricao=produto.nome_completo;
    }

    if(produto.descricao){
        descricao+=" - "+produto.descricao
    }

    return descricao;
}




ProdutoVendaModel.inicializeItems(); //iniciar em Edição
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

$('#ModalFormProduto').on('hidden.bs.modal',TelaProduto.resetFormProduto);
$('#ModalFormProduto').on('show.bs.modal',TelaProduto.inicialize);

$('#formProduto_produto_id').change(TelaProduto.onChangeProduto);
$("#formProduto_qtd").on("input",TelaProduto.onChangeQtd);
$("#formProduto_granel").on("input",TelaProduto.onChangeGranel);

$("#formProduto_qtd, #formProduto_valor_venda, #formProduto_granel ").on("change", function () {
    calculoTotal();
});



