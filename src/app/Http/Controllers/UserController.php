<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;
use App\Repositories\Methods;

class UserController extends Controller
{
    public function view(Request $request, User $user)
    {
    	$users = $user->all();
    	$msg = Methods::session_flash($request);

    	return view('auth.users', compact('users', 'msg'));
    }

    public function register(Request $request)
    {
    	if($request->query('email')){
    		$msg = 'Usuário ('. $request->query('email') .') incluido com sucesso!';
    		$request->session()->flash('msg_valid', $msg);
    	}
    	$msg = Methods::session_flash($request);
    	
    	return view('auth.register', [
    		'extends' => 'layouts.navbar', 
    		'section' => 'card-body', 
    		'msg' => $msg,
    		'route' => 'api.register'
    	]);
    }

    public function delete(Request $request, User $user)
    {
        $user = $user->find($request->id);
        $email = $user->email;
        $msg = 'Usuário ('. $email .') removido com sucesso!';
        $user->destroy($request->id);
        $request->session()->flash('msg_error', $msg); 
        return redirect()->route('admin.user.listView');
    }     
}
