<!-- Modal -->
<div class="modal fade" id="ModalFormProduto" tabindex="-1" role="dialog" aria-labelledby="TituloModalFormProduto" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalFormProduto">Novo Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=>'produtos.store','class'=>'','id'=>'adminForm2'])!!}
                    @include('produtos.form')

                {!! Form::close() !!}

              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" id="btnSaveNewProduto"
                class="btn btn-primary">
                Salvar
            </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>

    function updateSelect(produto){
       if(produto && produto.nome_completo){
        var newOption = new Option(produto.nome_completo, produto.id, true, true);
        $('#produto_id').append(newOption).trigger('change');
        closeModal();
       }
        
    }

    function closeModal(){
        $('#adminForm2')[0].reset();
        $('.groupSerVivo .no').button('toggle'); //valor padrão de ser Vivo
        $('#ModalFormProduto').modal('hide');
    }

    function saveNewProduto(e){
        e.preventDefault();
        $.ajax({
               url: '/produtos' ,
               type: "POST",
               data: $('#adminForm2').serialize(),
               success:function(produto){
                   updateSelect(produto);
               },
               error: function(err){
                   console.log(err.responseJSON.errors)
                   $.each(err.responseJSON.errors, function (i, error) {
                   var el = $(document).find('[name="'+i+'"]');
                   el.after($('<span class="ajaxErros" style="color: red;">'+error[0]+'</span>'));
               });
               },
               beforeSend:function(){
                   $(".ajaxErros").remove();
               }
            })
    
    }
        $("#btnSaveNewProduto").click(saveNewProduto);
       
    </script>
        
    
@endpush
