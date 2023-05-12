<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestLog extends Model
{
    protected $fillable = [
		'tanggal','name','company','date_from','date_to','reason','pic','phone','location','vaksin','question','answer','file'
	];
}
