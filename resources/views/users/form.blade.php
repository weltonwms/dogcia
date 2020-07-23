{{-- Usando o componentede Form bsText: forma mais curta que component blade FormGroup --}}
{{ Form::bsText('name',null,['label'=>"Nome *"]) }}
{{ Form::bsText('username',null,['label'=>"Nome de Usuario *"]) }}
{{ Form::bsText('email',null,['label'=>"Email *"]) }}
{{ Form::bsPassword('password',['label'=>isset($user)?'Senha':'Senha *']) }}






      