
<div class="row">
    <div class="col">
        <button type="button" class="btn btn-outline-success  pull-right" 
                data-toggle="modal"
                data-target="#ModalFormProduto"
                id="btn_add_item">
            <i class="fa fa-plus"></i>  Novo
        </button>

    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Valor Un</th>
                <th>Total</th>
                <th><i class="fa fa-edit"></i></th>
                <th><i class="fa fa-trash"></i></th>
            </tr>

        </thead>

        <tbody id="tbodyTableProduto">

        </tbody>
        
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td class="text-center table-primary"><b>Total Geral</b></td>
                <td class="table-primary"><span id="total_geral_tabela">0</span></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>




<!-- Modal -->
<div class="modal fade" id="ModalFormProduto" tabindex="-1" role="dialog" aria-labelledby="TituloModalFormProduto" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalFormProduto">Produto para Alugar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    
                    <div class="row">
                        <input type="hidden" id="formProduto_id" value="">
                        <div class="form-group col-md-8">
                            <label for="formProduto_produto" class="col-form-label">Produto:</label>
                            <select class="form-control" id="formProduto_produto_id" style="width: 100%">
                                <option value="">--Selecione--</option>
                                <?php foreach ($produtos as $produto): ?>
                                    <option value="<?php echo $produto->id ?>" data-obj="<?php echo base64_encode(json_encode($produto)) ?>">
                                        <?php echo $produto->nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="formProduto_qtd_disponivel" class="col-form-label">Qtd Disponível:</label>
                            <input class="form-control" type="text" id="formProduto_qtd_disponivel" readonly>
                        </div>


                        <div class="form-group col-md-4">
                            <label for="formProduto_qtd" class="col-form-label">Qtd:</label>
                            <input type="number" min="0" class="form-control" id="formProduto_qtd">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="formProduto_valor" class="col-form-label">Valor Un. Aluguel:</label>
                            <input type="text"  class="form-control money" id="formProduto_valor_aluguel">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="formProduto_total" class="col-form-label">Total:</label>
                            <input type="text" class="form-control" id="formProduto_total" readonly="readonly">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="btn_save_item">Salvar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript" src="{{ asset('js/produtoRent.js') }}"></script>
@endpush