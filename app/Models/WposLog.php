<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WposLog extends Model
{
    protected $fillable = [
    	'id',
		'tanggal',
		'company_name',
		'company_address',
		'company_email',
		'date_from',
		'date_to',
		'company_pic',
		'jabatan',
		'no_hp',
		'jenis_pekerjaan',
		'deskripsi',
		'lokasi',
		'bahaya',
		'lingkungan',
		'prosedur',
		'safety',
		'peringatan',
		'ketentuan',
		'pic_ympi',
		'departemen',
		'work_permit',
		'type',
		'location',
		'question1',
		'question2',
		'question3',
		'question4',
		'deleted_at',
		'created_at',
		'updated_at'
	];
}
