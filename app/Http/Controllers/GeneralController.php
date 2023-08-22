<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FixedAssetAudit;
use App\Models\FixedAssetCheck;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use Response;

class GeneralController extends Controller
{
  public function __construct()
  {
    $this->auditor = [
      ['nik' => 'PI9902017', 'name' => 'Romy Agung Kurniawan', 'email' => 'romy-agung.kurniawan@music.yamaha.com'],
      ['nik' => 'PI9802001', 'name' => 'Yeny Arisanty', 'email' => 'yeny.arisanty@music.yamaha.com'],
      ['nik' => 'PI0008009', 'name' => 'Agriyanto Sukmawan', 'email' => 'agriyanto.sukmawan@music.yamaha.com'],
      ['nik' => 'PI0902001', 'name' => 'Lailatul Chusnah', 'email' => 'lailatul.chusnah@music.yamaha.com'],
      ['nik' => 'PI0905001', 'name' => 'Ismail Husen', 'email' => 'ismail.husen@music.yamaha.com'],
      ['nik' => 'PI1505001', 'name' => 'Afifatuz Yulaichah', 'email' => 'afifatuz.yulaichah@music.yamaha.com'],
      ['nik' => 'PI1903018', 'name' => 'Mujahid Maruf', 'email' => 'mujahid.maruf@music.yamaha.com'],
      ['nik' => 'PI2203032', 'name' => 'Indah Oktavia Eka Damayanti', 'email' => 'afifatuz.yulaichah@music.yamaha.com']
    ];
}

  public function approvalFixedAssetCheck($location, $period, $stat, $position)
{
  if ($stat == 'Approved') {
      $nama = '';
      $status = true;
      $message2 = 'Successfully Approved';
      $stat2 = '';

      if ($position == 'chief') {
          $att = [];
          FixedAssetCheck::where('location', '=', $location)
          ->where('period', '=', $period)
          ->update([
              'appr_chief_by' => 'Adianto Heru P.',
              'appr_chief_at' => date('Y-m-d H:i:s'),
          ]);

          $asset_check = FixedAssetCheck::where('status', '=', 'Check 2')
          ->where('location', '=', $location)
          ->where('period', '=', $period)
          ->select('period', 'location', db::raw('count(sap_number) as total_asset'))
          ->groupBy('period', 'location')
          ->get();

          $summary_data = db::select("select location,
              SUM(IF(availability = 'Ada', 1, 0)) as ada,
              SUM(IF(availability = 'Tidak Ada', 1, 0)) as tidak_ada,
              SUM(IF(asset_condition = 'Rusak', 1, 0)) as rusak,
              SUM(IF(usable_condition = 'Tidak Digunakan', 1, 0)) as tidak_digunakan,
              SUM(IF(label_condition = 'Rusak', 1, 0)) as label_rusak,
              SUM(IF(map_condition = 'Tidak Sesuai', 1, 0)) as map_rusak,
              SUM(IF(asset_image_condition = 'Tidak Sesuai', 1, 0)) as image_rusak
              from fixed_asset_checks where `status` = 'Check 2' and location = '".$location."' and period = '".$period."'
              group by location");

          $data = [
              "datas" => $asset_check,
              "position" => 'Manager',
              "status" => 'Approve',
              "data_details" => $summary_data,
              "period" => $asset_check[0]->period,
              "att" => $att
          ];

          Mail::to(['imron.faizal@music.yamaha.com'])->bcc(['ismail.husen@music.yamaha.com','nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($data, 'fixed_asset_check'));
      } else if ($position == 'manager'){
          $att = [];
          FixedAssetCheck::where('location', '=', $location)
          ->where('period', '=', $period)
          ->update([
              'appr_manager_by' => 'Imron Faizal',
              'appr_manager_at' => date('Y-m-d H:i:s'),
          ]);

          $auditor_list = FixedAssetAudit::where('location', '=', $location)
          ->where('period', '=', $period)
          ->select('location','checked_by')
          ->groupBy('location', 'checked_by')
          ->get();

          $update_mirai = db::select("UPDATE ympimis.fixed_asset_checks
          LEFT JOIN ympimis_online.fixed_asset_checks on ympimis.fixed_asset_checks.location = ympimis_online.fixed_asset_checks.location AND ympimis.fixed_asset_checks.period = ympimis_online.fixed_asset_checks.period
          SET ympimis.fixed_asset_checks.appr_chief_by = ympimis_online.fixed_asset_checks.appr_chief_by,
          ympimis.fixed_asset_checks.appr_chief_at = ympimis_online.fixed_asset_checks.appr_chief_at,
          ympimis.fixed_asset_checks.appr_manager_by = ympimis_online.fixed_asset_checks.appr_manager_by,
          ympimis.fixed_asset_checks.appr_manager_at = ympimis_online.fixed_asset_checks.appr_manager_at,
          ympimis.fixed_asset_checks.appr_status = ympimis_online.fixed_asset_checks.appr_status
          WHERE ympimis_online.fixed_asset_checks.period = '".$period."' AND ympimis_online.fixed_asset_checks.location = '".$location."'");

          foreach ($auditor_list as $au_list) {
              $data_list = FixedAssetAudit::where('location', '=', $location)->where('period', '=', $period)->where('checked_by', '=', $au_list->checked_by)->select('location', 'location', db::raw('count(sap_number) as qty_asset'), 'period')->groupBy('location','location', 'period')->get();

              $auditor = explode('/', $au_list->checked_by)[0];

              $mailto = '';
              foreach ($this->auditor as $adt) {
                if ($adt['nik'] == $auditor) {
                  $mailto = $adt['email'];
                }
              }

              $summary_data = db::select("select location,
                  SUM(IF(availability = 'Ada', 1, 0)) as ada,
                  SUM(IF(availability = 'Tidak Ada', 1, 0)) as tidak_ada,
                  SUM(IF(asset_condition = 'Rusak', 1, 0)) as rusak,
                  SUM(IF(usable_condition = 'Tidak Digunakan', 1, 0)) as tidak_digunakan,
                  SUM(IF(label_condition = 'Rusak', 1, 0)) as label_rusak,
                  SUM(IF(map_condition = 'Tidak Sesuai', 1, 0)) as map_rusak,
                  SUM(IF(asset_image_condition = 'Tidak Sesuai', 1, 0)) as image_rusak
                  from fixed_asset_checks where `status` = 'Check 2' and location = '".$location."' and period = '".$period."'
                  group by location");

              $data = [
                  "datas" => $data_list,
                  "position" => 'Auditor',
                  "status" => '',
                  "data_details" => $summary_data,
                  "period" => $period,
                  "att" => $att
              ];

              Mail::to($mailto)->bcc(['ismail.husen@music.yamaha.com','nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($data, 'fixed_asset_check'));
          }
      }
  } else if($stat == 'Hold' || $stat == 'Reject'){
      $status = false;

      if ($stat == 'Hold') {
          $message2 = 'Hold & Comment';
      } else if($stat == 'Reject') {
          $message2 = 'Reject & Comment';
      }

      $stat2 = strtolower($stat);
      $nama = Auth::user()->username.'/'.Auth::user()->name;

      if ($position == 'chief') {
          FixedAssetCheck::where('location', '=', $location)
          ->where('period', '=', $period)
          ->update([
              'appr_chief_by' => Auth::user()->username.'/'.Auth::user()->name,
              'appr_chief_at' => date('Y-m-d H:i:s'),
          ]);
      } else if ($position == 'manager'){
          FixedAssetCheck::where('location', '=', $location)
          ->where('period', '=', $period)
          ->update([
              'appr_manager_by' => Auth::user()->username.'/'.Auth::user()->name,
              'appr_manager_at' => date('Y-m-d H:i:s'),
          ]);
      }
  }

  $title = 'Approval Audit Fixed Asset';
  $title_jp = '??';
  $message = 'Audit Fixed Asset';

  $asset = FixedAssetCheck::where('location', '=', $location)
  ->where('period', '=', $period)
  ->first();

  return view('fixed_asset.approval_message', array(
      'title' => $title,
      'title_jp' => $title_jp,
      'message' => $message,
      'message2' => $message2,
      'asset' => $asset,
      'status' => $status,
      'status2' => $stat2,
      'nama' => $nama
  ))->with('page', 'Fixed Asset Approval');
}
}
