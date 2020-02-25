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

    public function clienteView(Request $request)
    {
    	$clientes = Cliente::all();
    	$msg = $this->session_flash($request);
 	
    	return view('clientes.lista', compact('clientes', 'msg'));
    }

    public function clienteRegisterView(Request $request)
    {
    	$variables = array();
    	$tags = $this->tags;
    	$msg = $this->session_flash($request);

    	if ($request->id) {
	        $cliente = Cliente::find($request->id);
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

    public function clienteCreate(Request $request)
    {

        $cliente_exists = Cliente::where('email', $request->cliente_email)->first();

        if(!$cliente_exists){
            $cliente = Cliente::create([
                'name' => $request->cliente_name,
                'email' => $request->cliente_email,
            ]);
            $cliente->tag(explode(',', $request->tags));

            $msg_type = 'msg_valid';
            $msg = "O cliente ($cliente->email) foi adicionado com sucesso!";
        }else{
            $msg_type = 'msg_error';
            $msg = "O cliente ($cliente_exists->email) já existe no sistema!";
        }

        $request->session()->flash($msg_type, $msg);

        return redirect()->route('admin.cliente.listView');
    }

    public function clienteUpdate(Request $request)
    {
    	$cliente = Cliente::find($request->id);
    	$cliente->name = $request->cliente_name;
    	$cliente->email = $request->cliente_email;
    	$cliente->save();
    	// verifica se o cliente tem tags
    	if ($request->tags) {
    		//Deleta as tags atuais e salva as novas tags
    		$cliente->retag(explode(',', $request->tags));
    	}else{
    		// se estiver vazio, remove todas as tags do cliente
    		$cliente->untag();
    	}

        $msg_type = 'msg_valid';
        $msg = "O cliente ($cliente->email) foi alterado com sucesso!";        

        $request->session()->flash($msg_type, $msg);    	
        return redirect()->route('admin.cliente.listView');
    }

    public function clienteDelete(Request $request)
    {
        $cliente = Cliente::find($request->id);

        $email = $cliente->email;
        $msg = 'Usuário ('. $email .') removido com sucesso!';
        Cliente::destroy($request->id);
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
     * API
     */
    public function index()
    {
    	$cliente = Cliente::with('tagged')->get(); // eager load
        return $cliente;
    }

    public function show(Cliente $cliente)
    {
        return $cliente->with('tagged')->find($cliente->id); // eager load
    }

    public function store(Request $request)
    {
        $cliente = Cliente::create($request->all());
        $cliente->tag(explode(',', $request->tags));
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
