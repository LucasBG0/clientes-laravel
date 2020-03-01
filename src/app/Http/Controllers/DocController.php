<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;

class DocController extends Controller
{
    public function view()
    {
    	$slot = file_get_contents(resource_path('views/README.md'));
    	$extends = 'layouts.app';
    	$section = 'content';
    	return view('readme', compact('slot', 'extends', 'section'));
    }

    public function adminView()
    {
    	$slot = file_get_contents(resource_path('views/README.md'));
    	$extends = 'layouts.home';
    	$section = 'list';
    	return view('readme', compact('slot', 'extends', 'section'));
    }    
}
