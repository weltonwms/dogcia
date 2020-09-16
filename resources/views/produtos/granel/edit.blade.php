@extends('layouts.app')
@section('breadcrumb')
@breadcrumbs(['title'=>'Produtos', 'icon'=>'fa-balance-scale','route'=>route('produtos.index'),'subtitle'=>'Gerenciamento de Produto a Granel'])

@endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a href='' class="btn btn-sm btn-success mr-1 mb-1" onclick="adminFormSubmit(event)" > <i class="fa fa-plus"></i>Novo Granel</a>
<a class="btn btn-sm btn-outline-secondary mr-1 mb-1"  href="{{route('produtos.index')}}" > <i class="fa fa-close"></i>Cancelar</a>

@endtoolbar
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <p class="mb-0"><b>Produto:</b> {{$produto->getNomeCompleto()}}</p>
            <p class="mt-0"><b>Qtd Estoque:</b> {{$produto->qtd_estoque}}</p>


            {!! Form::open(['route'=>'produtos_granel.store','class'=>'','id'=>'adminForm'])!!}
            {!! Form::hidden('produto_id', $produto->id)!!}

             {!! Form::close() !!}

            <div class="card  bg-light ">
                <div class="card-body">
            
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Total Granel Dispnibilizado</label>
                               
                            <input readonly="readonly" type="text" class="form-control" value="{{Util::floatBr($granelDisponibilizado)." ".$produto->getNomeGrandeza() }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Total Granel Vendido</label>
                                <input readonly="readonly" id="custo_medio" type="text" class="form-control" value="{{Util::floatBr($granelVendido)." ".$produto->getNomeGrandeza()}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Total Granel Atual Disponível</label>
                                <input readonly="readonly" id="valor_venda" type="text" class="form-control" value="{{Util::floatBr($produto->granel)." ".$produto->getNomeGrandeza() }}">
                            </div>
                        </div>
                       
                    </div>
            
            
                </div>
            </div>
            <br>
            <h4 class="text-center">Disponibilizações a Granel</h4>

           <table class="table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Excluir</th>
                    </tr>
            
                </thead>
                <tbody>
                    @foreach($granos as $grano)
                    <tr>
                        <td>{{Util::dateBr($grano->created_at,'d/m/Y H:i')}}</td>
                        <td>{{Util::floatBr($grano->valor)." ".$produto->getNomeGrandeza()}}</td>
                        <td>
                            <a class="deleteGranel" href="{{route('produtos_granel.destroy',$grano->id)}}"
                                
                            >
                                <i class="fa fa-trash-o"></i>
                            </a>
                           
               
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <form id="delete-form" action="" method="POST" style="display: none;">
                <input type='hidden' name='_method' value='DELETE'>
                @csrf
            </form>





        </div>
    </div>

</div>


@push('scripts')
<script>
    function submitDelete(event){
        event.preventDefault();
        $("#delete-form").attr("action",this.href);
        $("#delete-form").submit();
        
    }

    $(".deleteGranel").click(submitDelete)
</script>
    
@endpush


@endsection