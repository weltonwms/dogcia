<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; //add para facilitar componente blade

use App\Observers\ProdutoRentObserver; //usado para eventos em ProdutoRent
use App\ProdutoRent;

use App\Observers\ProdutoVendaObserver; //usado para eventos em ProdutoVenda
use App\ProdutoVenda;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //facilitador do blade
        Blade::component('components.breadcrumbs', 'breadcrumbs');
        Blade::component('components.toolbar', 'toolbar');
        Blade::component('components.datatables', 'datatables');
        Blade::component('components.formgroup', 'formgroup');
        \Form::component('bsText', 'components.form.text', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('bsNumber', 'components.form.number', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('bsDate', 'components.form.date', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('bsPassword', 'components.form.password', ['name',  'attributes' => []]);
        \Form::component('bsSelect', 'components.form.select', ['name', 'list'=>[],'value'=>null, 'attributes' => []]);
        ProdutoRent::observe(ProdutoRentObserver::class);
        ProdutoVenda::observe(ProdutoVendaObserver::class);
    }
}
