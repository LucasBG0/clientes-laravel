<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;

class APIController extends Controller
{
	// All clientes tagged
	private $clientes;
	
	/*
	|--------------------------------------------------------------------------
	| API Methods
	|--------------------------------------------------------------------------
	|
	*/

	public function __construct()
	{
		$this->clientes = Cliente::with('tagged');
	}

    public function getAllClientes(Request $request)
    {
        $offset = $request->query('offset') ? $request->query('offset') : 0;
        $limit = $request->query('limit') ? $request->query('limit') : 50;
        $clientes = $this->clientes->offset($offset)->limit($limit)->get();

        return $clientes;
    }

    public function getCliente(Request $request)
    {
        $cliente = $this->clientes->find($request->id); //0.199 ms
        return response()->json($cliente, 200);
    }

    public function getAllTags(Request $request)
    {
    	$tags = Cliente::existingTags();
    	// if mode parameter is equal name returns only tag name
    	if ($field = $request->query('key')) {
    		$tags = $tags->pluck($field);
    	}
    	return response()->json($tags, 200);
    }

    public function store(Request $request)
    {	
    	// retorna o status 409 caso o e-mail já esteja cadastrado no sistema
    	$cliente_exists = $this->clientes->where('email', $request->email)->first(); // 56.593 ms
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
