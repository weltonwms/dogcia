<div class="row">
    <div class="col-lg-6">
        @formgroup(['label'=>'Nome *'])
        {{ Form::text('nome', null, ['class' => 'form-control','id'=>'nome']) }}
       <!--<input class="form-control"  id="nome" name="nome" type="text">-->
        @endformgroup

        {{ Form::bsText('email',null,['label'=>"Email *"]) }}

        @formgroup(['label'=>'Telefone *'])
        <input class="form-control phone"  id="telefone" value="{{ isset($cliente->telefone)?$cliente->telefone:old('telefone') }}" name="telefone" type="tel">
        @endformgroup
        @formgroup
        {{ Form::date('nascimento', null, ['class' => 'form-control','id'=>'nascimento']) }}
        @endformgroup

    </div>

    <div class="col-lg-6">
        {{-- Usando o componentede Form bsText: forma mais curta que component blade FormGroup --}}
        {{ Form::bsText('cep',null,['label'=>"CEP",'class'=>"cep"]) }}

        {{ Form::bsText('endereco',null,['label'=>"Endereço"]) }}
        
        {{ Form::bsText('cpf',NULL, ['label'=>"CPF", 'class'=>"cpf"]) }}
    </div>
</div>