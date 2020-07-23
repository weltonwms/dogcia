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
        $dados=[
            'name'=>"required",
            'email'=>"required|email|unique:users,email,$id",
            'username'=>"required|unique:users,username,$id",
            
        ];
        if(!$user):
            $dados['password']="required";
        endif;
            
        return $dados;
    }
}
