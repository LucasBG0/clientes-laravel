<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;

class ClienteController extends Controller
{
	private $tags;

	public function __construct()
	{
		$this->tags = Cliente::existingTags()->pluck('name');
	}

    public function clientView(Request $request)
    {
    	$clientes = $this->index(); // método GET da API
    	$msg = $this->session_flash($request);
 	
    	return view('clientes.lista', compact('clientes', 'msg'));
    }

    // Método utilizado para retornar a view no registro e na alteração de um cliente
    public function clientRegisterView(Request $request)
    {
    	$variables = array();
    	$tags = $this->tags;
    	$msg = $this->session_flash($request);

    	// se id == true então altera o cliente
    	if ($request->id) {
	        $cliente = $this->show($request); // método GET da API
	        $cliente_tags = implode(',', $cliente->tagNames());
	        $method = 'PUT';
	        $variables['cliente_tags'] = $cliente_tags;
	        $variables['cliente'] = $cliente;
    	}else{
	    	$method = 'POST';
    	}

    	$variables['tags'] = $tags;
    	$variables['method'] = $method;
    	$variables['msg'] = $msg;

    	return view('clientes.criar', $variables);
    }

    public function clientCreate(Request $request)
    {
        $cliente = $this->store($request); // método POST da API

        if($cliente->status() == 201){
            $msg_type = 'msg_valid';
            $msg = "O cliente ($request->email) foi adicionado com sucesso!";
        }else{
            $msg_type = 'msg_error';
            $msg = "O cliente ($request->email) já existe no sistema!";
        }

        $request->session()->flash($msg_type, $msg);

        return redirect()->route('admin.cliente.listView');
    }

    public function clientUpdate(Request $request)
    {
    	$cliente = $this->show($request); // método GET da API
    	$this->update($request, $cliente); // método PUT da API

        $msg_type = 'msg_valid';
        $msg = "O cliente ($cliente->email) foi alterado com sucesso!";        

        $request->session()->flash($msg_type, $msg);    	
        return redirect()->route('admin.cliente.listView');
    }

    public function clientDelete(Request $request)
    {
        $cliente = $this->show($request); // método GET da API
        $this->delete($cliente); // método DELETE da API
        
        $email = $cliente->email;
        $msg = 'Usuário ('. $email .') removido com sucesso!';
        
        $request->session()->flash('msg_error', $msg); 
        return redirect()->route('admin.cliente.listView');
    }

    // método auxliar para flash message
    private function session_flash($request)
    {
        if ( $request->session()->has('msg_valid') ) {
            $msg['text'] = $request->session()->get('msg_valid');
            $msg['type'] = 'success';
        }elseif( $request->session()->has('msg_error') ){
            $msg['text'] = $request->session()->get('msg_error');
            $msg['type'] = 'danger';
        }else{
            $msg = '';
        }
        return $msg;
    }


	/*
	|--------------------------------------------------------------------------
	| API Methods
	|--------------------------------------------------------------------------
	|
	*/
    public function index()
    {
    	// All clientes
    	$cliente = Cliente::with('tagged')->get(); // eager load
        return $cliente;
    }

    public function show(Request $request)
    {
    	// specific cliente
    	$cliente = Cliente::find($request->id);
        return $cliente->with('tagged')->find($cliente->id); // eager load
    }

    public function store(Request $request)
    {	
    	// retorna o status 409 caso o e-mail já esteja cadastrado no sistema
    	$cliente_exists = Cliente::where('email', $request->email)->first();
    	if ($cliente_exists) {
    		return response()->json(['success' => false], 409);
    	}

        $cliente = Cliente::create($request->all());
    	// verifica se tem tags na requisição
    	if ($request->tags) {
    		//Associa as novas tags ao modelo cliente
    		$cliente->tag(explode(',', $request->tags));
    	}        
        $cliente = $cliente->with('tagged')->find($cliente->id);

        return response()->json($cliente, 201);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($request->all());
    	// verifica se tem tags na requisição
    	if ($request->tags) {
    		//Deleta as tags atuais e salva as novas tags
    		$cliente->retag(explode(',', $request->tags));
    	}else{
    		// se estiver vazio, remove todas as tags do cliente
    		$cliente->untag();
    	}

        $cliente = $cliente->with('tagged')->find($cliente->id);

        return response()->json($cliente, 200);
    }

    public function delete(Cliente $cliente)
    {
        $cliente->delete();

        return response()->json(null, 204);
    }
}
