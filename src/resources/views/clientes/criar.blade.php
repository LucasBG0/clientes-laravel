@extends('clientes.navbar', compact('msg'))

@section('clientes')
<div class="row">
    <div class="col">
      <div class="card">
          <div class="card-header">{{ $method == 'PUT' ? 'Alterar' : 'Criar' }} cliente</div>
          <div class="card-body">
              <form method="post" action="{{ ($method == 'PUT') ? Route('admin.cliente.alterar', $cliente->id) : '' }}">
                @csrf
                @method($method)
                <div class="form-group row">    
                    <label for="name" class="col-sm-3 col-form-label">Nome do cliente</label>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control form-control-lg" name="name" 
                    	id="cliente_name" placeholder="João da Silva" value="{{ $cliente->name ?? '' }}">
                	</div>
                </div>
                <div class="form-group row">    
                    <label for="name" class="col-sm-3 col-form-label">E-mail do cliente</label>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control form-control-lg" name="email" 
                    	id="cliente_email" placeholder="joao_silva@gmail.com" value="{{ $cliente->email ?? '' }}">
                    </div>	
                </div>  
                <div class="form-group row">   
                    <label for="name" class="col-sm-3 col-form-label">Tags</label>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control form-control-lg" name="tags" 
                    	id="tags" placeholder="participantes da Maratona" value="{{ $cliente_tags ?? '' }}">
                    </div>	
                </div>                                
                <div class="form-group row">
                	<div class="col-auto">
                    	<button class="btn btn-dark btn-lg">{{ $method == 'PUT' ? 'Alterar' : 'Criar' }} cliente</button>
                    </div>	
                </div>
              </form>             
          </div> 
      </div>
    </div>
</div>
<script>
var tags = [
    @foreach ($tags as $tag)
    {tag: "{{$tag}}" },
    @endforeach
];
</script>
@endsection