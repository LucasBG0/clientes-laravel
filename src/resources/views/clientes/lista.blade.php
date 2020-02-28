@extends('clientes.navbar', compact('msg'))

@section('clientes')
<div class="row">
  <div class="col">
    <div class="card">
        <div class="card-header">Todos clientes</div>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="d-none d-sm-inline">Nome</span></th>
                <th scope="col"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span><span class="d-none d-sm-inline">E-mail</span></th>
                <th scope="col"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span><span class="d-none d-sm-inline">Tags</span></th>
                <th scope="col"><span class="glyphicon glyphicon-time" aria-hidden="true"></span><span class="d-none d-lg-inline d-xl-inline">Data de criação</span></th>
                <th scope="col">alterar</th>
                <th scope="col">excluir</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($clientes as $cliente)
              <tr>
                <th scope="row"><div class="my-2">{{ $cliente->id }}</div></th>
                <td scope="col"><div class="my-2">{{ $cliente->name }}</div></td>
                <td scope="col"><div class="my-2">{{ $cliente->email }}</div></td>
                <td scope="col">
            	@foreach ($cliente->tagged as $tag)
            		<div class="d-inline-block rounded p-1 my-1 bg-primary text-white tag">{{ $tag->tag_name }}</div>
            	@endforeach
                </td>
                <td scope="col">{{ $cliente->created_at ? $cliente->created_at : '--' }}</td>
                <td>
                  <form method="post" action="{{ Route('admin.cliente.alteraView', $cliente->id) }}">
                    @csrf
                    <button class="btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                  </form>
                </td> 
                <td>
                  <form method="post" action="{{ Route('admin.cliente.delete', $cliente->id) }}" onsubmit="return confirm('Tem certeza?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                  </form>
                </td>                  
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>  

    </div>          
  </div>
</div>
@endsection