<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CmsVendor extends Model
{
    protected $fillable = [
		'tanggal','name','company','question','answer','file'
	];
}
