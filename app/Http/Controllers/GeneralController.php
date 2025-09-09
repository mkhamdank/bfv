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

    function searchDriverJob($id)
    {
        $plat_no = base64_decode($id);
        $driver_task = DB::table('driver_tasks')
        ->where('plat_no',$plat_no)
        ->where('remark','japanese')
        ->where('closure_status','driver')
        ->whereDate('date_from','<=',date('Y-m-d'))
        ->orderby('id','asc')
        ->first();

        if($driver_task){
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
        if($driver_lists){
            $attendance = DB::table('attendances')
            ->where('employee_id',$driver_lists->driver_id)
            ->whereDate('datetime',date('Y-m-d'))
            ->first();
        }

        return view('general_affair.driver.index_confirm_daily_task')
        ->with('id',$id)
        ->with('plat_no',$plat_no)
        ->with('driver_lists',$driver_lists)
        ->with('japanese',$japanese)
        ->with('attendance',$attendance)
        ->with('status','success')
        ->with('title','Konfirmasi Daily Driver Task')
        ->with('title_jp','日次ドライバータスクの確認')
        ->with('message','Konfirmasi Daily Driver Task')
        ->with('message_jp','日次ドライバータスクの確認');
    }

    function inputConfirmationDriverDailyJob(Request $request)
    {
        try {
            $hour_start = $request->get('hour_start');
            $hour_end = $request->get('hour_end');
            $date = $request->get('date');
            $id = $request->get('id');
            $plat_no = $request->get('plat_no');
            $driver_list_id = $request->get('driver_list_id');
            $japanese_id = $request->get('japanese_id');

            $driver_lists = DB::table('driver_lists')
            ->where('id',$driver_list_id)
            ->first();

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
                'date_from' => $date.' '.$hour_start.':00',
                'date_to' => $date.' '.$hour_end.':00',
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

            // $phone = '6282334197238';

            $message = '';

            $message .= "_*DRIVER ORDER*_\\n";
            $message .= "\\nTugas Anda telah dikonfirmasi.\\n";
            $message .= "\\nKlik tautan di bawah jika ada biaya lain-lain (Tol & Parkir).\\n";
            $link = url('') . '/index/additional/driver/daily_job/'.$insert_driver_task;
            $message .= $link . "\\n";
            $message .= "\\nAbaikan jika tidak ada biaya tambahan.\\n";
            $message .= "\\n-YMPI GA Dept.-";

            $fuel_actual_after = 0;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.whatspie.com/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "device": "6281130561777",
                "receiver": "' . $phone . '",
                "type": "chat",
                "message": "' . $message . '",
                "simulate_typing": 1
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer UAqINT9e23uRiQmYttEUiFQ9qRMUXk8sADK2EiVSgLODdyOhgU',
                ),
            ));
            curl_exec($curl);

            curl_close($curl);

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

                // $phone = '6282334197238';

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

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.whatspie.com/messages',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                    "device": "6281130561777",
                    "receiver": "' . $phone . '",
                    "type": "chat",
                    "message": "' . $message . '",
                    "simulate_typing": 1
                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization: Bearer UAqINT9e23uRiQmYttEUiFQ9qRMUXk8sADK2EiVSgLODdyOhgU',
                    ),
                ));
                curl_exec($curl);

                curl_close($curl);

                // $curl = curl_init();

                // curl_setopt_array($curl, array(
                //     CURLOPT_URL => 'https://vsms-v2-public.mceasy.com/v1/vehicles',
                //     CURLOPT_SSL_VERIFYHOST => false,
                //     CURLOPT_SSL_VERIFYPEER => false,
                //     CURLOPT_RETURNTRANSFER => true,
                //     CURLOPT_ENCODING => '',
                //     CURLOPT_MAXREDIRS => 10,
                //     CURLOPT_TIMEOUT => 0,
                //     CURLOPT_FOLLOWLOCATION => true,
                //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //     CURLOPT_CUSTOMREQUEST => 'GET',
                //     // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                //     // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
                //     CURLOPT_HTTPHEADER => array(
                //         'Accept: application/json',
                //         'Content-Type: application/x-www-form-urlencoded',
                //         'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
                //     ),
                // ));
                // $response = curl_exec($curl);

                // curl_close($curl);

                // $datas = json_decode($response)->data;

                // $id_vehicle = '';

                // for ($i=0; $i < count($datas); $i++) { 
                //     if ($datas[$i]->licensePlate == $driver_task->plat_no) {
                //         $id_vehicle = $datas[$i]->id;
                //     }
                // }

                // $ada_data = 'Tidak';

                // $data_vehicle = null;
                // $data_vehicle_fuel = null;

                // if ($id_vehicle != '') {
                //     $curl = curl_init();

                //     curl_setopt_array($curl, array(
                //         CURLOPT_URL => 'https://vsms-v2-public.mceasy.com/v1/vehicles/'.$id_vehicle.'',
                //         CURLOPT_SSL_VERIFYHOST => false,
                //         CURLOPT_SSL_VERIFYPEER => false,
                //         CURLOPT_RETURNTRANSFER => true,
                //         CURLOPT_ENCODING => '',
                //         CURLOPT_MAXREDIRS => 10,
                //         CURLOPT_TIMEOUT => 0,
                //         CURLOPT_FOLLOWLOCATION => true,
                //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //         CURLOPT_CUSTOMREQUEST => 'GET',
                //         // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                //         // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
                //         CURLOPT_HTTPHEADER => array(
                //             'Accept: application/json',
                //             'Content-Type: application/x-www-form-urlencoded',
                //             'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
                //         ),
                //     ));
                //     $response = curl_exec($curl);

                //     curl_close($curl);

                //     $data_vehicle = json_decode($response)->data;

                //     $curl = curl_init();

                //     curl_setopt_array($curl, array(
                //         CURLOPT_URL => 'https://vsms-v2-public.mceasy.com/v1/vehicles/'.$id_vehicle.'/status',
                //         CURLOPT_SSL_VERIFYHOST => false,
                //         CURLOPT_SSL_VERIFYPEER => false,
                //         CURLOPT_RETURNTRANSFER => true,
                //         CURLOPT_ENCODING => '',
                //         CURLOPT_MAXREDIRS => 10,
                //         CURLOPT_TIMEOUT => 0,
                //         CURLOPT_FOLLOWLOCATION => true,
                //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //         CURLOPT_CUSTOMREQUEST => 'GET',
                //         // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                //         // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
                //         CURLOPT_HTTPHEADER => array(
                //             'Accept: application/json',
                //             'Content-Type: application/x-www-form-urlencoded',
                //             'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
                //         ),
                //     ));
                //     $response = curl_exec($curl);

                //     curl_close($curl);

                //     $data_vehicle_fuel = json_decode($response)->data;

                //     if ($data_vehicle_fuel) {
                //         $fuel_actual_after = ($data_vehicle_fuel->fuelFiltered/100)*$data_vehicle_fuel->fuelCapacity;
                //     }

                //     // $ada_data = 'Ada';
                // }

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

    

    function indexConfirmationDriverJob($id)
    {
        $task_id = base64_decode($id);
        $driver_task = DB::table('driver_tasks')
        ->where('task_id',$task_id)
        ->where('closure_status','driver')
        ->first();

        $japanese = null;
        if($driver_task){
            $japanese = DB::table('japaneses')
            ->where('employee_id',$driver_task->requested_id)
            ->first();
        }

        if($driver_task){
            return view('general_affair.driver.index_confirm_task')
            ->with('driver_task',$driver_task)
            ->with('id',$id)
            ->with('japanese',$japanese)
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
            $date = $request->get('date');
            $id = $request->get('id');
            $task_id = $request->get('task_id');

            $driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->first();

            $update_driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'date_from' => $date.' '.$hour_start.':00',
                'date_to' => $date.' '.$hour_end.':00',
                'closure_status' => 'japanese',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if(substr($driver_task->driver_phone, 0, 1) == '+' ){
                $phone = substr($driver_task->driver_phone, 1, 15);
            }
            else if(substr($driver_task->driver_phone, 0, 1) == '0'){
                $phone = "62".substr($driver_task->driver_phone, 1, 15);
            }
            else{
                $phone = $driver_task->driver_phone;
            }

            // $phone = '6282334197238';

            $message = '';

            $message .= "_*DRIVER ORDER*_\\n";
            $message .= "\\nTugas Anda telah dikonfirmasi.\\n";
            $message .= "\\nKlik tautan di bawah jika ada biaya lain-lain (Tol & Parkir).\\n";
            $link = url('') . '/index/additional/driver/job/'.$task_id;
            $message .= $link . "\\n";
            $message .= "\\nAbaikan jika tidak ada biaya tambahan.\\n";
            $message .= "\\n-YMPI GA Dept.-";

            $fuel_actual_after = 0;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.whatspie.com/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "device": "6281130561777",
                "receiver": "' . $phone . '",
                "type": "chat",
                "message": "' . $message . '",
                "simulate_typing": 1
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer UAqINT9e23uRiQmYttEUiFQ9qRMUXk8sADK2EiVSgLODdyOhgU',
                ),
            ));
            curl_exec($curl);

            curl_close($curl);

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
            $etoll = $request->get('etoll');
            $parking = $request->get('parking');
            $id = $request->get('id');
            $task_id = $request->get('task_id');

            //ETOLL

            if($etoll != null && $etoll != ''){

                $data_foto = null;

                for ($i = 0; $i < count($request->file('file_etoll')); ++$i) {
                    $tujuan_upload = 'images/driver/japanese/additional';

                    $file_etoll = $request->file('file_etoll')[$i];
                    $nama_foto = $file_etoll->getClientOriginalName();
                    $extension_foto = pathinfo($nama_foto, PATHINFO_EXTENSION);
                    $filename_foto = 'Foto Etoll '.$id.' ('.date('d-M-y H-i-s').')['.$i.'].'.$extension_foto;
                    $file_etoll->move($tujuan_upload,$filename_foto);
                    $data_foto[]=$filename_foto;
                }
                $file_upload_foto_etoll = join(',',$data_foto);

                $update = DB::table('driver_tasks')
                ->where('id',$id)
                ->update([
                    'etoll' => $etoll,
                    'etoll_file' => $file_upload_foto_etoll,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            if($etoll == null || $etoll == ''){
                $update = DB::table('driver_tasks')
                ->where('id',$id)
                ->update([
                    'etoll' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            //PARKING

            if($parking != null && $parking != ''){

                $data_foto = null;

                for ($i = 0; $i < count($request->file('file_parking')); ++$i) {
                    $tujuan_upload = 'images/driver/japanese/additional';

                    $file_parking = $request->file('file_parking')[$i];
                    $nama_foto = $file_parking->getClientOriginalName();
                    $extension_foto = pathinfo($nama_foto, PATHINFO_EXTENSION);
                    $filename_foto = 'Foto Parkir '.$id.' ('.date('d-M-y H-i-s').')['.$i.'].'.$extension_foto;
                    $file_parking->move($tujuan_upload,$filename_foto);
                    $data_foto[]=$filename_foto;
                }
                $file_upload_foto_parking = join(',',$data_foto);

                $update = DB::table('driver_tasks')
                ->where('id',$id)
                ->update([
                    'parking' => $parking,
                    'parking_file' => $file_upload_foto_parking,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            if($parking == null || $parking == ''){
                $update = DB::table('driver_tasks')
                ->where('id',$id)
                ->update([
                    'parking' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

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
