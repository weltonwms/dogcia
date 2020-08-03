<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user=$this->route('user');
        $id=$user?$user->id:null;
        $ruleEmail= request('email')?"email|unique:users,email,$id":"";
        $dados=[
            'name'=>"required",
            'email'=>$ruleEmail,
            'username'=>"required|unique:users,username,$id",
            'perfil'=>"required"
            
        ];
        if(!$user):
            $dados['password']="required";
        endif;
            
        return $dados;
    }
}
