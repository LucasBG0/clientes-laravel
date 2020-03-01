<?php

namespace App\Repositories;

class Methods
{
    public static function session_flash($request)
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