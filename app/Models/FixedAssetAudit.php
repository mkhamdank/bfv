<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAssetAudit extends Model
{

	protected $table = 'fixed_asset_audits';
	protected $fillable = ['period','category','location','sap_number','asset_name','asset_section','asset_map','asset_images','result_images','note','availability','asset_condition','label_condition','usable_condition','map_condition','asset_image_condition','pic','status','result_video','checked_by','checked_date','remark', 'audit_type','created_by',
	];

	public function user()
	{
		return $this->belongsTo('App\User', 'created_by')->withTrashed();
	}
}
