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
        //colocar pv em itemsModel
        items.forEach(addPvItem);
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
            var txDesc= 1 - item.desconto/100;
            var total = parseFloat(item.qtd) * parseFloat(item.valor_venda * txDesc);
            return total.toFixed(2);
        }
    }

    function getValorFinal(index){
        var item = items[index];
        if (item) {
            var txDesc= 1 - item.desconto/100;
            var valorFinal = parseFloat(item.valor_venda * txDesc);
            return valorFinal.toFixed(2);
        }
    }
   

    function saveItem() {
        var id = $("#formProduto_id").val().trim();
        var produto_id = $("#formProduto_produto_id").val();
        var qtd = lerInputNumber("#formProduto_qtd");
        var desconto = lerInputNumber("#formProduto_desconto");
        var valor_venda = ler_valor("#formProduto_valor_venda");
        var custo_medio=ler_valor("#formProduto_custo_medio");
        var granel=$("#formProduto_granel").is(":checked")?1:0;
        
        var item = {produto_id: produto_id, qtd: qtd, valor_venda: valor_venda,custo_medio:custo_medio, 
            granel:granel,desconto:desconto};
        addPvItem(item);
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
                    "<td>" + valorFormatado(getValorFinal(i)) + "</td>" +
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
    var desconto=0;
    
    //atributos abaixo não são usados devido a suposições de valores de currentProduto ou produtoVendaModel
    var valor_venda; 
    var margem;
    var custo_medio;
    var desconto_maximo=0;
   
    function inicialize(){
        //Espécie de Construtor do Modal. Colocar ações pararelas ao mostrar tela.
        showBlocoCm();
        showBlocoDesconto();
    }

   

    function setCurrentProduto(produto_id){
        currentProduto=produto_id?getObjProduct(produto_id):null;
        //ao mudar o currentProduto, setar o model relacionado a esse produto se houver
        produtoVendaModel=ProdutoVendaModel.getProdutoVenda(produto_id);
       
        //ao mudar o currentProduto atualizar o campo desconto com maxímo permitido
        updateDescontoMaximo();
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
            if(isGranel){
                var grand= currentProduto.valor_grandeza;
                return produtoVendaModel.pv/grand;
            }
           return produtoVendaModel.pv;
        }

        if(currentProduto){
            if(isGranel){
                var grand= currentProduto.valor_grandeza; 
                return currentProduto.valor_venda/grand;
            }
            return currentProduto.valor_venda;
        }
  
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

    function updateDescontoMaximo(){
        $(".MensagemDescontoMaximo").html('');
        $("#formProduto_desconto").attr("max",getDescontoMaximo());
        showBlocoDesconto();
    }

    function getDescontoMaximo(){
        var descMax=0;
        if(currentProduto){
            descMax=currentProduto.desconto_maximo;
        }
        return descMax;
    }

    function showMessageDescontoMaximo(){
        $(".MensagemDescontoMaximo").html('');
        if(desconto > getDescontoMaximo()){
            $(".MensagemDescontoMaximo").html('O desconto Máximo é '+getDescontoMaximo()+'%.');
        }
        
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

    function showBlocoDesconto(){
        if(getDescontoMaximo()<=0){
            $(".blocoDesconto").hide();
        }
        else{
            $(".blocoDesconto").show();
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
       var vl=getValorVenda();
       $("#formProduto_valor_venda").val( valorFormatado(vl) );
        writeValorFinal();
    }

    function writeValorFinal(){
        var txDesc= 1 - desconto/100;
        var vl= getValorVenda() * txDesc;
        $("#formProduto_valor_final").val(valorFormatado(vl));
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
        $("#formProduto_desconto").val(produtoVendaModel.desconto);
        desconto=produtoVendaModel.desconto;
        isGranel=produtoVendaModel.granel==1;
        $("#formProduto_granel").prop("checked", isGranel);
        //avisar o select2 da mudança ; chamada implicita de write()
        $('#formProduto_produto_id').trigger('change'); 

    }

    function setDesconto(valor){
        if(!valor){
            valor=0;
        }
        desconto=parseFloat(valor);
    }


    function onChangeQtd(event){
        setQtd(this.value);
        $("#formProduto_qtd_estoque").val(getQtdDisponivelAtual());
    }

    function onChangeDesconto(event){
        setDesconto(this.value);
        writeValorFinal();
        calculoTotal();
        showMessageDescontoMaximo();
    }

    function onChangeGranel(event){
        //setGranel(this.value);
        isGranel= $(this).is(":checked");
        showBlocoCm();
        showInfoGrandeza();
       
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
        desconto=0;
        //valor_venda=null;
        $("#formProduto_id").val('');
        $("#formProduto_produto_id").val('');
        $("#formProduto_qtd").val('');
        $("#formProduto_valor_venda").val('');
        $("#formProduto_valor_final").val('');
        $("#formProduto_total").val('');
        $("#formProduto_qtd_estoque").val('');
        $("#formProduto_custo_medio").val('');
        $("#formProduto_custo_medio_granel").val('');
        
        $("#formProduto_granel").prop("checked", false);
        $("#formProduto_margem").val('');
        $("#formProduto_desconto").val('');
        $('#formProduto_produto_id').trigger('change'); //avisar o select2 da mudança
    }

    function getDesconto(){
        return desconto;
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
        onChangeGranel:onChangeGranel,
        onChangeDesconto:onChangeDesconto,
        writeValorFinal:writeValorFinal,
        getDesconto:getDesconto
    };
})();
//Classe usada para saber o que já tem gravado no backend. Útil para cálculo de qtd Disponível
ItensGravados=(function(){
    var items=[];

    function setItems(){
        var valor= $('#itensGravados').val() || '[]';
        items = JSON.parse(valor);
        //Object.assign(items,itemsGravados);
        tratarItems();
        console.log('itensGravados: ', items)
    }

    function getQtdGravadaByProduto(produto_id){
        var obj=items.find(function(item){
            return item.produto_id==produto_id;
        });
        return obj?parseFloat(obj.qtd):0;
    }

    function tratarItems(){
        //colocar pv em itemsModel
        items.forEach(addPvItem);
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

//necessário objeto item possuir atributos: produto_id, granel e valor_venda
function addPvItem(item){
    if(item.granel){
        var obj=getObjProduct(item.produto_id);
        var grand= obj.valor_grandeza
        item.pv=item.valor_venda*grand; //recuperando valor_venda sem quebra do granel;
    }
    else{
        item.pv=item.valor_venda;
    }
}

function calculoTotal() {
    var qtd = lerInputNumber("#formProduto_qtd");
    var valor_venda = ler_valor("#formProduto_valor_venda");
    var desconto = lerInputNumber("#formProduto_desconto");
    if(!desconto){
        desconto=0;
    }
    var txDesc= 1 - desconto/100;

    if (qtd && valor_venda) {
        var total = qtd * valor_venda;
        $("#formProduto_total").val(valorFormatado(total*txDesc));

    } else {
        $("#formProduto_total").val('');
    }
}



function checkErrors(){
    var qtd= TelaProduto.getQtd();
    var valor_venda= ler_valor("#formProduto_valor_venda");
    var desconto = lerInputNumber("#formProduto_desconto");
    var descontoMaximo=TelaProduto.getCurrentProduto()?TelaProduto.getCurrentProduto().desconto_maximo:0;
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
    if(desconto>descontoMaximo){
        errors.push("Desconto Maior que permitido: "+descontoMaximo+"%");
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
$("#formProduto_desconto").on("input",TelaProduto.onChangeDesconto);

$("#formProduto_qtd,  #formProduto_granel ").on("input", function () {
    calculoTotal();
});





