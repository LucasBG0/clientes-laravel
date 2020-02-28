<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use GuzzleHttp\Client as ApiRequest;

class ClienteController extends Controller
{

    public function clientView(Request $request)
    {
    	//$clientes = $this->getAllClientes(); // método GET da API
    	try{
	    	$api = new ApiRequest;
	    	$response = $api->request('GET', Route('api.getAllClientes'), [
	    		'headers' => [
	    			'Accept' => 'application/json', 
	    			'Content-Type' => 'application/json',
	    			'Authorization' => 'Bearer '. \Auth::user()->api_token
	    		],
	    		'http_errors' => false
	    	]);
	    	$clientes = json_decode($response->getBody()->getContents());
	    	$msg = $this->session_flash($request);    		
    	}catch (RequestException $e){
    		return redirect()->route('home');
    	}

 	
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
	        $cliente = $this->getCliente($request); // método GET da API
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
    	$cliente = $this->getCliente($request); // método GET da API
    	$this->update($request, $cliente); // método PUT da API

        $msg_type = 'msg_valid';
        $msg = "O cliente ($cliente->email) foi alterado com sucesso!";        

        $request->session()->flash($msg_type, $msg);    	
        return redirect()->route('admin.cliente.listView');
    }

    public function clientDelete(Request $request)
    {
        $cliente = $this->getCliente($request); // método GET da API
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
}
