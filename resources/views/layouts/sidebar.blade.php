<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">

  <ul class="app-menu">
    <li>
      <a class="app-menu__item {{Request::segment(1)=='home'?'active':null}}" href="{{ url('/home') }}">
        <i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Painel de Controle</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='clientes'?'active':null}}" href="{{route('clientes.index')}}">
        <i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Clientes</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='produtos'?'active':null}}" href="{{route('produtos.index')}}">
        <i class="app-menu__icon fa fa-gift"></i><span class="app-menu__label">Produtos</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='rents'?'active':null}}" href="{{route('rents.index')}}">
        <i class="app-menu__icon fa fa-taxi"></i>
        <span class="app-menu__label">Aluguéis</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='vendas'?'active':null}}" href="{{route('vendas.index')}}">
        <i class="app-menu__icon fa fa-cart-plus"></i><span class="app-menu__label">Vendas</span>
      </a>
    </li>

    <li class="treeview">
      <a class="app-menu__item {{Request::segment(1)=='relatorio'?'active':null}}" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Relatórios</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <li>
          <a class="treeview-item {{Request::segment(2)=='rents'?'active':null}}" href="{{route('relatorio.rents')}}">
            <i class="icon fa fa-circle-o"></i> Alugueis
          </a>
        </li>
        <li>
          <a class="treeview-item {{Request::segment(2)=='produtoRent'?'active':null}}"
            href="{{route('relatorio.produtoRent')}}">
            <i class="icon fa fa-circle-o"></i> Produtos Alugados
          </a>
        </li>
        <li>
          <a class="treeview-item {{Request::segment(2)=='vendas'?'active':null}}" href="{{route('relatorio.vendas')}}">
            <i class="icon fa fa-circle-o"></i> Vendas
          </a>
        </li>
        <li>
          <a class="treeview-item {{Request::segment(2)=='produtoVenda'?'active':null}}"
            href="{{route('relatorio.produtoVenda')}}">
            <i class="icon fa fa-circle-o"></i> Produtos Vendidos
          </a>
        </li>
      </ul>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='users'?'active':null}}" href="{{route('users.index')}}">
        <i class="app-menu__icon fa fa-user-secret"></i>
        <span class="app-menu__label">Usuários</span>
      </a>
    </li>

  </ul>

</aside>