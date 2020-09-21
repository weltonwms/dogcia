<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::resource('clientes', 'ClienteController');
    Route::delete('/clientes_bath','ClienteController@destroyBath' )->name('clientes_bath.destroy');

    Route::resource('sellers', 'SellerController');
    Route::delete('/sellers_bath','SellerController@destroyBath' )->name('sellers_bath.destroy');
    
    Route::resource('produtos', 'ProdutoController');
    Route::delete('/produtos_bath','ProdutoController@destroyBath' )->name('produtos_bath.destroy');
    Route::get('produtos_granel/{produto}','GranoController@edit')->name('produtos_granel.edit');
    Route::post('produtos_granel','GranoController@store')->name('produtos_granel.store');
    Route::delete('produtos_granel/{grano}','GranoController@destroy')->name('produtos_granel.destroy');

    Route::resource('compras', 'CompraController');
    Route::delete('/compras_bath','CompraController@destroyBath' )->name('compras_bath.destroy');
    
    Route::resource('mortes', 'MorteController');
    Route::delete('/mortes_bath','MorteController@destroyBath' )->name('mortes_bath.destroy');

    Route::resource('vendas', 'VendaController');
    Route::delete('/vendas_bath','VendaController@destroyBath' )->name('vendas_bath.destroy');
    Route::get('vendas/{venda}/print ','VendaController@print')->name('vendas.print');
    Route::get('vendas/{venda}/detailAjax ','VendaController@detailAjax')->name('vendas.detailAjax');

    
    Route::get('users/changePassword','UserController@showChangePassword')->name('users.change');
    Route::post('users/changePassword','UserController@updatePassword')->name('users.updatePass');
    Route::resource('users', 'UserController');
    Route::delete('/users_bath','UserController@destroyBath' )->name('users_bath.destroy');

    Route::match(['get', 'post'],"relatorio/vendas",'RelatorioController@vendas')->name('relatorio.vendas');
    Route::match(['get', 'post'],"relatorio/produtoVenda",'RelatorioController@produtoVenda')->name('relatorio.produtoVenda');
    Route::match(['get', 'post'],"relatorio/produtos",'RelatorioController@produtos')->name('relatorio.produtos');
    Route::match(['get', 'post'],"relatorio/sellers",'RelatorioController@sellers')->name('relatorio.sellers');


    Route::get('teste1','TesteController@teste1');
    Route::get('teste2','TesteController@teste2');

});
