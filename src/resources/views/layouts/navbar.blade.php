@extends('layouts.home')

@section('list')
<div class="container my-5">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header p-0">
                    <nav class="navbar navbar-expand navbar-light bg-light p-0">
                      <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                          @if ( strpos(Route::currentRouteName(), 'admin.cliente') !== false )
                          <li class="nav-item py-3 {{ Route::currentRouteNamed('admin.cliente.listView') ? 'active bg-'.Config::get('theme_color') : '' }}">
                            <a class="nav-link" href="{{ route('admin.cliente.listView') }}">
                              <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                              <div class="d-none d-sm-inline">Todos clientes</div>
                          	</a>
                          </li>
                          <li class="nav-item py-3 {{ Route::currentRouteNamed('admin.cliente.registerView') ? 'active bg-'.Config::get('theme_color') : '' }}">
                            <a class="nav-link" href="{{ route('admin.cliente.registerView') }}">
                              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                              <div class="d-none d-sm-inline">Criar cliente</div>
                          	</a>
                          </li>
                          @else
                          <li class="nav-item py-3 {{ Route::currentRouteNamed('admin.user.listView') ? 'active bg-'.Config::get('theme_color') : '' }}">
                            <a class="nav-link" href="{{ route('admin.user.listView') }}">
                              <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                              <div class="d-none d-sm-inline">Todos usuários</div>
                            </a>
                          </li>
                          <li class="nav-item py-3 {{ Route::currentRouteNamed('admin.user.register') ? 'active bg-'.Config::get('theme_color') : '' }}">
                            <a class="nav-link" href="{{ route('admin.user.register') }}">
                              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                              <div class="d-none d-sm-inline">Criar usuário</div>
                            </a>
                          </li>                          
                          @endif
                        </ul>
                      </div>
                    </nav>
                </div>   
                <div class="card-body">
                  @if( !empty($msg) )
                  <div class="alert alert-{{ $msg['type'] }} mt-3" role="alert">
                    {{ $msg['text'] }}
                  </div>
                  @endif
                  <div class="p-4">
                    @yield('card-body')
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection