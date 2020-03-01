@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
          <div class="sidebar-heading">
            <span class="glyphicon glyphicon-user rounded-circle btn-{{ Config::get('theme_color') }}" aria-hidden="true"></span>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto d-inline-block">
                <!-- Authentication Links -->
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle text-{{ Config::get('theme_color') !== 'light' ? Config::get('theme_color') : 'dark' }}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Sair') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>    
          </div>
          <div class="list-group list-group-flush">
            <a href="{{ Route('admin.cliente.listView') }}" class="list-group-item list-group-item-action {{ strpos(Route::currentRouteName(), 'admin.cliente') !== false ? 'active btn-'.Config::get('theme_color') : '' }}">
                <span class="glyphicon glyphicon-home" aria-hidden="true"></span>Clientes
            </a>
            <a href="{{ Route('admin.user.listView') }}" 
                class="list-group-item list-group-item-action {{ strpos(Route::currentRouteName(), 'admin.user') !== false ? 'active btn-'.Config::get('theme_color') : '' }}">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>Usuários
            </a>   
          </div>
        </div>           
        <!-- /#sidebar-wrapper -->
        <div class="col p-0">
            <div id="page-content-wrapper">
                <nav class="navbar navbar-expand navbar-{{ Config::get('theme_color') }} bg-{{ Config::get('theme_color') }} shadow-sm">
                    <button class="navbar-toggler d-block" id="menu-toggle"><span class="navbar-toggler-icon"></span></button>
                </nav>                
                @yield('list') 
            </div>            
        </div>                         
    </div>
</div>  
@endsection
