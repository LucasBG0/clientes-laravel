<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use GuzzleHttp\Client as ApiRequest;

class ClienteController extends Controller
{

    public function clientView(Request $request)
    {
        $clientes = $this->apiRequest('GET', '/api/clientes'); // requisição GET para a API - api/clientes
    	$msg = $this->session_flash($request);    		
        return view('clientes.lista', compact('clientes', 'msg'));
    }

    // Método utilizado para retornar a view no registro e na alteração de um cliente
    public function clientRegisterView(Request $request)
    {
    	$variables = array();
    	$msg = $this->session_flash($request);

    	// se id == true então altera o cliente
    	if ($request->id) {
            $cliente = $this->apiRequest('GET', '/api/clientes/'.$request->id); // requisição GET para a API - api/clientes/{id}
            $cliente_tags = collect($cliente->tagged)->pluck('tag_name')->toArray();
	        $cliente_tags = implode(',', $cliente_tags);
	        $method = 'PUT';
	        $variables['cliente_tags'] = $cliente_tags;
	        $variables['cliente'] = $cliente;
    	}else{
	    	$method = 'POST';
    	}

        $tags = $this->apiRequest('GET', '/api/tags?key=name'); // requisição GET para a API - api/tags
    	$variables['tags'] = $tags;
    	$variables['method'] = $method;
    	$variables['msg'] = $msg;

    	return view('clientes.criar', $variables);
    }

    public function clientCreate(Request $request)
    {
        $cliente = $this->apiRequest('POST', '/api/clientes', $request->all()); // método POST da API

        if(!isset($cliente->success)){
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
    	$cliente = $this->apiRequest('GET', '/api/clientes/'.$request->id); // método GET da API
    	$this->apiRequest('PUT', '/api/clientes/'.$request->id, $request->all()); // método PUT da API

        $msg_type = 'msg_valid';
        $msg = "O cliente ($cliente->email) foi alterado com sucesso!";        

        $request->session()->flash($msg_type, $msg);    	
        return redirect()->route('admin.cliente.listView');
    }

    public function clientDelete(Request $request)
    {
        $cliente = $this->apiRequest('GET', '/api/clientes/'.$request->id); // método GET da API
        $this->apiRequest('DELETE', '/api/clientes/'.$request->id); // método DELETE da API
        
        $email = $cliente->email;
        $msg = 'O cliente ('. $email .') removido com sucesso!';
        
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
    | Métodos que consomem a API
    |--------------------------------------------------------------------------
    |
    */
    private function apiRequest(string $method, string $endpoint, array $data = null)
    {
        $rootUrl = 'http://gerenciador-clientes-nginx';
        $api = new ApiRequest(['base_uri' => $rootUrl]);

        $params = array(
            'headers' => [
                'Accept' => 'application/json', 
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '. \Auth::user()->api_token
            ],
            'http_errors' => false
        );

        if ($data) {
            $params['json'] = $data;
        }
        
        $response = $api->request($method, $endpoint, $params);
        $cliente = json_decode($response->getBody()->getContents());
        return $cliente;          
    }
}
