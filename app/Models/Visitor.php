<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Visitor extends Model
{
	use SoftDeletes;
	protected $fillable = [
		'company','purpose','status','location','destination','jumlah','remark','employee','reason','transport','pol'
	];
}

