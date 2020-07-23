<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserSecurityRequest;


class UserController extends Controller
{


    public function showChangePassword()
    {
        return view('users.change-password');
    }

    public function updatePassword(UserSecurityRequest $request)
    {

        $user = \Auth::user();
        $new_password = $request->input('password');
        $user->password = \Hash::make($new_password);
        $user->save();
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('Senha Alterada com Sucesso!')]);
        return back();
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view("users.index", compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $dados=  $this->tratarDados($request->all());
        $user=User::create($dados);
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('users.index');
        endif;
        return redirect()->route('users.edit',$user->id);
        
    }

    /**
     * Display the specified resource.
     *
     * @param   \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        unset($user->password);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $dados=  $this->tratarDados($request->all());
        $user->update($dados);
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('users.index');
        endif;
        return redirect()->route('users.edit',$user->id);
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy( User $user)
    {
        //$retorno = $user->verifyAndDelete();
       $retorno = $user->delete();
       if ($retorno):
           \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
       endif;

       return redirect()->route('users.index');
    }

    public function destroyBath()
    {
      $retorno= User::destroy(request('ids'));
     if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;

        return redirect()->route('users.index');
    }

    private function tratarDados($dados)
    {
        if($dados['password']):
            $dados['password']=  bcrypt($dados['password']);
        else:
            unset($dados['password']);
        endif;
        return $dados;
    }
}
