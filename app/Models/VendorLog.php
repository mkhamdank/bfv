<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorLog extends Model
{
    protected $fillable = [
		'tanggal','name','company','result','file'
	];
}
