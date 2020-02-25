<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Conner\Tagging\Taggable;

class Cliente extends Model
{
	use Taggable;

	protected $fillable = ['id', 'name', 'email'];
	protected $table = 'clientes';
}