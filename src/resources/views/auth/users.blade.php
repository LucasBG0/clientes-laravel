@extends('layouts.navbar', compact('msg'))

@section('card-body')
<div class="row">
    <div class="col">
      <div class="card">
          <div class="card-header">Todos usuários</div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nome</th>
                  <th scope="col">E-mail</th>
                  <th scope="col">Data de criação</th>
                  <th scope="col">excluir</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                <tr>
                  <th scope="row">{{ $user->id }}</th>
                  <th scope="col">{{ $user->name }}</th>
                  <th scope="col">{{ $user->email }}</th>
                  <th scope="col">{{ $user->created_at ? $user->created_at : '--' }}</th>
                  <td>
                    <form method="post" action="{{ Route('admin.user.delete', $user->id) }}" onsubmit="return confirm('Tem certeza?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span></button>
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