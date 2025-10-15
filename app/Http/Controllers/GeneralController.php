<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FixedAssetAudit;
use App\Models\FixedAssetCheck;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

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

    function searchDriverJob($id)
    {
        $plat_no = base64_decode($id);
        $driver_task = DB::table('driver_tasks')
        ->where('plat_no',$plat_no)
        ->whereIn('remark',['japanese','reguler'])
        ->where('closure_status','driver')
        ->whereDate('date_from','<=',date('Y-m-d'))
        ->whereNull('deleted_at')
        ->orderby('id','asc')
        ->get();

        if(count($driver_task) > 0){
            return view('general_affair.driver.index_search_driver_job')
            ->with('driver_task',$driver_task)
            ->with('id',$id)
            ->with('plat_no',$plat_no)
            ->with('status','success_found')
            ->with('title','Konfirmasi Driver Order')
            ->with('title_jp','ドライバー注文の確認')
            ->with('message','Konfirmasi Driver Order')
            ->with('message_jp','ドライバー注文の確認');
        }else{
            return view('general_affair.driver.index_search_driver_job')
            ->with('status','success_not_found')
            ->with('id',$id)
            ->with('plat_no',$plat_no)
            ->with('title','Konfirmasi Daily Driver Task')
            ->with('title_jp','日次ドライバータスクの確認')
            ->with('message','Konfirmasi Daily Driver Task')
            ->with('message_jp','日次ドライバータスクの確認');
        }
    }

    function indexConfirmationDriverDailyJob($id) {
        $plat_no = base64_decode($id);

        $driver_lists = DB::table('driver_lists')
        ->where('plat_no',$plat_no)
        ->first();

        $japanese = null;
        if($driver_lists){
            $japanese = DB::table('japaneses')
            ->where('employee_id',$driver_lists->passenger_id)
            ->first();
        }

        $attendance = null;
        $timestamp_attendance = date('Y-m-d').' 05:00:00';
        if($driver_lists){
            $attendance = DB::table('attendances')
            ->where('employee_id',$driver_lists->driver_id)
            ->whereDate('datetime',date('Y-m-d'))
            ->first();
            if($attendance){
                $timestamp_attendance = $attendance->datetime;
            }
        }
        if(!$japanese){
            return view('general_affair.driver.index_confirm_daily_task')
            ->with('status','error')
            ->with('title','Konfirmasi Daily Driver Task')
            ->with('title_jp','日次ドライバータスクの確認')
            ->with('message','User Not Detected')
            ->with('message_jp','ユーザーが検出されませんでした');
        }

        if(!$driver_lists){
            return view('general_affair.driver.index_confirm_daily_task')
            ->with('status','error')
            ->with('title','Konfirmasi Daily Driver Task')
            ->with('title_jp','日次ドライバータスクの確認')
            ->with('message','Driver Not Detected')
            ->with('message_jp','ドライバーが検出されませんでした');
        }

        return view('general_affair.driver.index_confirm_daily_task')
        ->with('id',$id)
        ->with('plat_no',$plat_no)
        ->with('driver_lists',$driver_lists)
        ->with('japanese',$japanese)
        ->with('attendance',$attendance)
        ->with('timestamp_attendance',$timestamp_attendance)
        ->with('status','success')
        ->with('title','Konfirmasi Daily Driver Task')
        ->with('title_jp','日次ドライバータスクの確認')
        ->with('message','Konfirmasi Daily Driver Task')
        ->with('message_jp','日次ドライバータスクの確認');
    }

    function getVehicle($plat_no) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://vsms-v2-public.mceasy.com/v1/vehicles',
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
            ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);

        $datas = json_decode($response)->data;

        $id_vehicle = '';

        for ($i=0; $i < count($datas); $i++) { 
            if ($datas[$i]->licensePlate == $plat_no) {
                $id_vehicle = $datas[$i]->id;
            }
        }

        $ada_data = 'Tidak';

        $data_vehicle = null;
        $data_vehicle_fuel = null;

        if ($id_vehicle != '') {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://vsms-v2-public.mceasy.com/v1/vehicles/'.$id_vehicle.'',
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
                ),
            ));
            $response = curl_exec($curl);

            curl_close($curl);

            $data_vehicle = json_decode($response)->data;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://vsms-v2-public.mceasy.com/v1/vehicles/'.$id_vehicle.'/status',
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
                ),
            ));
            $response = curl_exec($curl);

            curl_close($curl);

            $data_vehicle_fuel = json_decode($response)->data;

            // $ada_data = 'Ada';
        }
        $odometer = 0;
        if ($data_vehicle != null) {
            $odometer = $data_vehicle->odometer;
        }
        $fuel = 0;
        if ($data_vehicle_fuel != null) {
            $fuel = ($data_vehicle_fuel->fuelFiltered / 100) * $data_vehicle_fuel->fuelCapacity;
        }
        $latitude = 0;
        $longitude = 0;
        if ($data_vehicle_fuel != null) {
            $latitude = $data_vehicle_fuel->latitude;
            $longitude = $data_vehicle_fuel->longitude;
        }
        $data = [
            'odometer' => $odometer,
            'fuel' => $fuel,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        return $data;
    }

    function inputConfirmationDriverDailyJob(Request $request)
    {
        try {
            $hour_start = $request->get('hour_start');
            $hour_end = $request->get('hour_end');
            $minute_start = $request->get('minute_start');
            $minute_end = $request->get('minute_end');
            $date = $request->get('date');
            $id = $request->get('id');
            $plat_no = $request->get('plat_no');
            $driver_list_id = $request->get('driver_list_id');
            $japanese_id = $request->get('japanese_id');

            $driver_lists = DB::table('driver_lists')
            ->where('id',$driver_list_id)
            ->first();

            $odometer = $this->getVehicle($plat_no)['odometer'];
            $fuel = round($this->getVehicle($plat_no)['fuel'],2);
            $latitude = $this->getVehicle($plat_no)['latitude'];
            $longitude = $this->getVehicle($plat_no)['longitude'];

            $japanese = DB::table('japaneses')
            ->where('id',$japanese_id)
            ->first();

            $task_id = 'Daily_' . date('Ymd');
            $insert_driver_task = DB::table('driver_tasks')
            ->insertGetId([
                'task_id' => $task_id,
                'created_by_id' => $japanese->employee_id,
                'created_by_name' => $japanese->employee_name,
                'driver_id' => $driver_lists->driver_id,
                'driver_name' => $driver_lists->driver_name,
                'driver_phone' => $driver_lists->whatsapp_no,
                'plat_no' => $plat_no,
                'car' => $driver_lists->car,
                'odometer' => $odometer,
                'fuel' => $fuel,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'date_from' => $date.' '.$hour_start.':'.$minute_start.':00',
                'date_to' => $date.' '.$hour_end.':'.$minute_end.':00',
                'purpose' => 'Pekerjaan',
                'remark' => 'daily_japanese',
                'requested_id' => $japanese->employee_id,
                'requested_name' => $japanese->employee_name,
                'closure_status' => 'daily_japanese',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if(substr($driver_lists->whatsapp_no, 0, 1) == '+' ){
                $phone = substr($driver_lists->whatsapp_no, 1, 15);
            }
            else if(substr($driver_lists->whatsapp_no, 0, 1) == '0'){
                $phone = "62".substr($driver_lists->whatsapp_no, 1, 15);
            }
            else{
                $phone = $driver_lists->whatsapp_no;
            }

            if(php_sapi_name() === 'cli' || (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '10.109.33.34')){
                $phone = '6282334197238';
            }

            $driver_task = DB::table('driver_tasks')
            ->where('id',$insert_driver_task)
            ->first();

            $message = '';

            $message .= "_*DRIVER ORDER*_\\n";
            $message .= "\\nTugas Anda telah dikonfirmasi.\\n";
            
            $message .= "\\nDetail Tugas:\\n";
            $message .= "Nama Driver: " . $driver_task->driver_name . "\\n";
            $message .= "Plat No: " . $driver_task->plat_no . "\\n";
            $message .= "Mobil: " . $driver_task->car . "\\n";
            $message .= "Tanggal: " . date('d-m-Y', strtotime($driver_task->date_from)) . "\\n";
            $message .= "Jam Mulai: " . date('H:i', strtotime($driver_task->date_from)) . "\\n";
            $message .= "Jam Selesai: " . date('H:i', strtotime($driver_task->date_to)) . "\\n";
            $message .= "Diminta Oleh: " . $driver_task->requested_name . "\\n";
            $message .= "Tujuan: " . ($driver_task->purpose ?? '-') . "\\n";

            // $message .= "\\nKlik tautan di bawah jika ada biaya lain-lain (Tol & Parkir).\\n";
            // $link = 'https://ympi.co.id/index/additional/driver/daily_job/'.$insert_driver_task;
            // $message .= $link."\\n";
            // $message .= "\\nAbaikan jika tidak ada biaya tambahan.\\n";
            $message .= "\\nData dapat dicek di website.\\n";

            $message .= "\\nBiaya tol dan parkir dapat diinput melalui website.\\n";
            $message .= "\\n-YMPI GA Dept.-";

            app(WhatsappController::class)->whatspie($phone, $message);

            $response = array(
                'status' => true,
                'message' => 'Success Input Data (データの入力に成功しました)'
            );
            return Response::json($response);
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage()
            );
            return Response::json($response);
        }
    }

    function closureDriverJob($id)
    {
        $task_id = base64_decode($id);
        $driver_task = DB::table('driver_tasks')
        ->where('task_id',$task_id)
        ->first();

        if($driver_task){
            if($driver_task->closure_status == null){
                if(substr($driver_task->requested_phone, 0, 1) == '+' ){
                    $phone = substr($driver_task->requested_phone, 1, 15);
                }
                else if(substr($driver_task->requested_phone, 0, 1) == '0'){
                    $phone = "62".substr($driver_task->requested_phone, 1, 15);
                }
                else{
                    $phone = $driver_task->requested_phone;
                }

                if(php_sapi_name() === 'cli' || (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '10.109.33.34')){
                    $phone = '6282334197238';
                }

                $message = '';

                $message .= "_*DRIVER ORDER*_\\n";
                $message .= "_*ドライバー注文*_\\n";
                $message .= "\\nDriver telah menyelesaikan tugas.\\n";
                $message .= "運転手がタスクを完了しました。\\n";
                $message .= "\\nKlik tautan di bawah untuk input data.\\n";
                $message .= "以下のリンクをクリックしてデータを入力してください。\\n\\n";
                $link = url('').'/index/confirmation/driver/job/'.$id;
                $message .= $link . "\\n";
                $message .= "\\n-YMPI GA Dept.-";

                $fuel_actual_after = 0;

                app(WhatsappController::class)->whatspie($phone, $message);

                
                $update_driver_task = DB::table('driver_tasks')
                ->where('task_id',$task_id)
                ->update([
                    'fuel_actual_after' => $fuel_actual_after,
                    'closure_status' => 'driver',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                return view('general_affair.driver.closure_task')
                ->with('status','success')
                ->with('message','Tugas Telah Diselesaikan.<br>Infokan ke User untuk konfirmasi tugas melalui Whatsapp.');
            }else{
                return view('general_affair.driver.closure_task')
                ->with('status','success')
                ->with('message','Anda telah menyelesaikan tugas ini.');
            }
        }else{
            return view('general_affair.driver.closure_task')
            ->with('status','error')
            ->with('message','Tugas Tidak Ditemukan');
        }
    }

    

    function indexConfirmationDriverJob($id,$id_daily)
    {
        $task_ids = [];
        $task_id = explode('_',$id);
        for ($i=0; $i < count($task_id); $i++) { 
            $task_ids[] = base64_decode($task_id[$i]);
        }
        $driver_task = DB::table('driver_tasks')
        ->whereIn('task_id',$task_ids)
        ->where('closure_status','driver')
        ->get();

        $japanese = null;
        $driver_task_id_with_otp = [];
        for ($i=0; $i < count($driver_task); $i++) { 
            $japanese = DB::table('japaneses')
            ->where('employee_id',$driver_task[$i]->requested_id)
            ->first();
            if($japanese){
                array_push($driver_task_id_with_otp,$driver_task[$i]->id.'_'.$japanese->driver_otp);
            }else{
                $pins = DB::table('driver_pins')
                ->where('task_id',$driver_task[$i]->task_id)
                ->first();
                array_push($driver_task_id_with_otp,$driver_task[$i]->id.'_'.$pins->token);
            }
        }

        //JAPANESE
        $plat_no = base64_decode($id_daily);
        $driver_lists = DB::table('driver_lists')
        ->where('plat_no',$plat_no)
        ->first();

        $calendar = DB::table('weekly_calendars')
        ->where('week_date',date('Y-m-d'))
        ->first();

        $japanese = null;
        $japanese_id = null;
        if($driver_lists){
            $japanese = DB::table('japaneses')
            ->where('employee_id',$driver_lists->passenger_id)
            ->first();
            if($japanese){
                $japanese_id = $japanese->id;
                if($calendar->remark != 'H'){
                    array_unshift($driver_task_id_with_otp,'daily_'.$japanese->driver_otp);
                }
            }
        }

        $attendance = null;
        $driver_list_id = null;
        $timestamp_attendance = date('Y-m-d').' 05:00:00';
        $driver_name = null;
        if($driver_lists){
            $driver_name = $driver_lists->driver_name;
            $driver_list_id = $driver_lists->id;
            $attendance = DB::table('attendances')
            ->where('employee_id',$driver_lists->driver_id)
            ->whereDate('datetime',date('Y-m-d'))
            ->first();
            if($attendance){
                $timestamp_attendance = $attendance->datetime;
            }
        }
        
        if($driver_task){
            return view('general_affair.driver.index_confirm_task')
            ->with('driver_task',$driver_task)
            ->with('id',$id)
            ->with('driver_task_id_with_otp',$driver_task_id_with_otp)
            ->with('driver_task',$driver_task)
            ->with('id_daily',$id_daily)
            ->with('plat_no',$plat_no)
            ->with('timestamp_attendance',$timestamp_attendance)
            ->with('japanese_id',$japanese_id)
            ->with('driver_list_id',$driver_list_id)
            ->with('driver_name',$driver_name)
            ->with('status','success')
            ->with('title','Konfirmasi Driver Order')
            ->with('title_jp','ドライバー注文の確認')
            ->with('message','Konfirmasi Driver Order')
            ->with('message_jp','ドライバー注文の確認');
        }else{
            return view('general_affair.driver.index_confirm_task')
            ->with('status','error')
            ->with('title','Konfirmasi Driver Order')
            ->with('title_jp','ドライバー注文の確認')
            ->with('message','Driver Order Anda telah dikonfirmasi')
            ->with('message_jp','ドライバー注文が確認されました');
        }
    }

    function inputConfirmationDriverJob(Request $request)
    {
        try {
            $hour_start = $request->get('hour_start');
            $hour_end = $request->get('hour_end');
            $minute_start = $request->get('minute_start');
            $minute_end = $request->get('minute_end');
            $date = $request->get('date');
            $id = $request->get('id');
            $task_id = base64_encode($request->get('task_id'));

            $driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->first();

            $plat_no = $driver_task->plat_no;
            $odometer = $this->getVehicle($plat_no)['odometer'];
            $fuel = round($this->getVehicle($plat_no)['fuel'],2);
            $latitude = $this->getVehicle($plat_no)['latitude'];
            $longitude = $this->getVehicle($plat_no)['longitude'];

            $update_driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'odometer' => $odometer,
                'fuel' => $fuel,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'date_from' => $date.' '.$hour_start.':'.$minute_start.':00',
                'date_to' => $date.' '.$hour_end.':'.$minute_end.':00',
                'closure_status' => 'japanese',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->first();

            if(substr($driver_task->driver_phone, 0, 1) == '+' ){
                $phone = substr($driver_task->driver_phone, 1, 15);
            }
            else if(substr($driver_task->driver_phone, 0, 1) == '0'){
                $phone = "62".substr($driver_task->driver_phone, 1, 15);
            }
            else{
                $phone = $driver_task->driver_phone;
            }

            if(php_sapi_name() === 'cli' || (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '10.109.33.34')){
                $phone = '6282334197238';
            }

            $message = '';

            $message .= "_*DRIVER ORDER*_\\n";
            $message .= "\\nTugas Anda telah dikonfirmasi.\\n";

            $message .= "\\nDetail Tugas:\\n";
            $message .= "Nama Driver: " . $driver_task->driver_name . "\\n";
            $message .= "Plat No: " . $driver_task->plat_no . "\\n";
            $message .= "Mobil: " . $driver_task->car . "\\n";
            $message .= "Tanggal: " . date('d-m-Y', strtotime($driver_task->date_from)) . "\\n";
            $message .= "Jam Mulai: " . date('H:i', strtotime($driver_task->date_from)) . "\\n";
            $message .= "Jam Selesai: " . date('H:i', strtotime($driver_task->date_to)) . "\\n";
            $message .= "Diminta Oleh: " . $driver_task->requested_name . "\\n";
            $message .= "Tujuan: " . ($driver_task->purpose ?? '-') . "\\n";

            $message .= "\\nData dapat dicek di website.\\n";

            $message .= "\\nBiaya tol dan parkir dapat diinput melalui website.\\n";
            $message .= "\\n-YMPI GA Dept.-";

            $fuel_actual_after = 0;

            app(WhatsappController::class)->whatspie($phone, $message);

            $response = array(
                'status' => true,
                'message' => 'Success Input Data (データの入力に成功しました)'
            );
            return Response::json($response);
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage()
            );
            return Response::json($response);
        }
    }

    function indexAdditionalDriverJob($id)
    {
        $task_id = base64_decode($id);
        $driver_task = DB::table('driver_tasks')
        ->where('task_id',$task_id)
        ->where('closure_status','japanese')
        ->first();

        if($driver_task){
            return view('general_affair.driver.index_additional_task')
            ->with('driver_task',$driver_task)
            ->with('id',$id)
            ->with('status','success')
            ->with('title','Tambahan Biaya Driver Order')
            ->with('title_jp','')
            ->with('message','Tambahan Biaya Driver Order')
            ->with('message_jp','');
        }else{
            return view('general_affair.driver.index_additional_task')
            ->with('status','error')
            ->with('title','Tambahan Biaya Driver Order')
            ->with('title_jp','')
            ->with('message','Tambahan biaya sudah diinput.')
            ->with('message_jp','');
        }
    }

    function inputAdditionalDriverJob(Request $request)
    {
        try {
            // $etoll = $request->get('etoll');
            // $parking = $request->get('parking');
            $id = $request->get('id');
            $task_id = $request->get('task_id');

            // //ETOLL
            // if($etoll != null && $etoll != ''){

            //     $data_foto = null;

            //     for ($i = 0; $i < count($request->get('file_etoll')); ++$i) {
            //         $tujuan_upload = 'images/driver/japanese/additional';

            //         $file_etoll = $request->get('file_etoll')[$i];
            //         $file_etoll1 = explode(',', $file_etoll)[1];
            //         $file_etoll1 = str_replace(' ', '+', $file_etoll1);
            //         $data = base64_decode($file_etoll1);
            //         $file_etoll_name = 'Foto Etoll '.$id.' ('.date('d-M-y H-i-s').')['.$i.'].png';
            //         file_put_contents($tujuan_upload.'/'.$file_etoll_name, $data);
            //         $data_foto[]=$file_etoll_name;
            //     }
            //     $file_upload_foto_etoll = join(',',$data_foto);

            //     $update = DB::table('driver_tasks')
            //     ->where('id',$id)
            //     ->update([
            //         'etoll' => $etoll,
            //         'etoll_file' => $file_upload_foto_etoll,
            //         'updated_at' => date('Y-m-d H:i:s')
            //     ]);
            // }

            // if($etoll == null || $etoll == ''){
                $update = DB::table('driver_tasks')
                ->where('id',$id)
                ->update([
                    'etoll' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            // }

            // //PARKING
            // if($parking != null && $parking != ''){

            //     $data_foto = null;

            //     for ($i = 0; $i < count($request->get('file_parking')); ++$i) {
            //         $tujuan_upload = 'images/driver/japanese/additional';

            //         $file_parking = $request->get('file_parking')[$i];
            //         $file_parking1 = explode(',', $file_parking)[1];
            //         $file_parking1 = str_replace(' ', '+', $file_parking1);
            //         $data = base64_decode($file_parking1);
            //         $file_parking_name = 'Foto Parkir '.$id.' ('.date('d-M-y H-i-s').')['.$i.'].png';
            //         file_put_contents($tujuan_upload.'/'.$file_parking_name, $data);
            //         $data_foto[]=$file_parking_name;
            //     }
            //     $file_upload_foto_parking = join(',',$data_foto);

            //     $update = DB::table('driver_tasks')
            //     ->where('id',$id)
            //     ->update([
            //         'parking' => $parking,
            //         'parking_file' => $file_upload_foto_parking,
            //         'updated_at' => date('Y-m-d H:i:s')
            //     ]);
            // }

            // if($parking == null || $parking == ''){
                $update = DB::table('driver_tasks')
                ->where('id',$id)
                ->update([
                    'parking' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            // }

            $update = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'closure_status' => 'closed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $response = array(
                'status' => true,
                'message' => 'Success Input Data (データの入力に成功しました)'
            );
            return Response::json($response);
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage()
            );
            return Response::json($response);
        }
    }

    function inputAdditionalDriverJobEtoll(Request $request)
    {
        try {
            $etoll = $request->get('etoll');
            $file_etoll = $request->get('file_etoll');
            $index = $request->get('index');
            $id = $request->get('id');
            $task_id = $request->get('task_id');

            $tujuan_upload = 'images/driver/japanese/additional';

            $file_etoll = $request->get('file_etoll');
            $file_etoll1 = explode(',', $file_etoll)[1];
            $file_etoll1 = str_replace(' ', '+', $file_etoll1);
            $data = base64_decode($file_etoll1);
            $file_etoll_name = 'Foto Etoll '.$id.' ('.date('d-M-y H-i-s').')['.$index.'].png';
            file_put_contents($tujuan_upload.'/'.$file_etoll_name, $data);

            $get_etoll = DB::table('driver_tasks')
            ->where('id',$id)
            ->first();

            $etoll_value = null;
            $etoll_file = null;

            if($get_etoll->etoll != null){
                $etoll_value = $get_etoll->etoll.','.$etoll;
                $etoll_file = $get_etoll->etoll_file.','.$file_etoll_name;
            }else{
                $etoll_value = $etoll;
                $etoll_file = $file_etoll_name;
            }

            $update = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'etoll' => $etoll_value,
                'etoll_file' => $etoll_file,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $response = array(
                'status' => true,
                'message' => 'Success Input Data Etoll (ETOLLデータの入力に成功しました)'
            );
            return Response::json($response);
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage()
            );
            return Response::json($response);
        }
    }

    function inputAdditionalDriverJobParking(Request $request)
    {
        try {
            $parking = $request->get('parking');
            $file_parking = $request->get('file_parking');
            $index = $request->get('index');
            $id = $request->get('id');
            $task_id = $request->get('task_id');

            $tujuan_upload = 'images/driver/japanese/additional';

            $file_parking = $request->get('file_parking');
            $file_parking1 = explode(',', $file_parking)[1];
            $file_parking1 = str_replace(' ', '+', $file_parking1);
            $data = base64_decode($file_parking1);
            $file_parking_name = 'Foto Parking '.$id.' ('.date('d-M-y H-i-s').')['.$index.'].png';
            file_put_contents($tujuan_upload.'/'.$file_parking_name, $data);

            $get_parking = DB::table('driver_tasks')
            ->where('id',$id)
            ->first();

            $parking_value = null;
            $parking_file = null;

            if($get_parking->parking != null){
                $parking_value = $get_parking->parking.','.$parking;
                $parking_file = $get_parking->parking_file.','.$file_parking_name;
            }else{
                $parking_value = $parking;
                $parking_file = $file_parking_name;
            }

            $update = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'parking' => $parking_value,
                'parking_file' => $parking_file,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $response = array(
                'status' => true,
                'message' => 'Success Input Data Parking (駐車データの入力に成功しました)'
            );
            return Response::json($response);
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage()
            );
            return Response::json($response);
        }
    }

    function indexAdditionalDriverDailyJob($id)
    {
        $driver_task = DB::table('driver_tasks')
        ->where('id',$id)
        ->where('closure_status','daily_japanese')
        ->first();

        if($driver_task){
            return view('general_affair.driver.index_additional_task')
            ->with('driver_task',$driver_task)
            ->with('id',$id)
            ->with('status','success')
            ->with('title','Tambahan Biaya Driver')
            ->with('title_jp','')
            ->with('message','Tambahan Biaya Driver')
            ->with('message_jp','');
        }else{
            return view('general_affair.driver.index_additional_task')
            ->with('status','error')
            ->with('title','Tambahan Biaya Driver')
            ->with('title_jp','')
            ->with('message','Tambahan biaya sudah diinput.')
            ->with('message_jp','');
        }
    }
}
