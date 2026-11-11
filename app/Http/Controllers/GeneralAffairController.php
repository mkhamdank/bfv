<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Response;
use App\Models\CodeGenerator;
use Illuminate\Support\Facades\Auth;

class GeneralAffairController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexDriverJob()
    {
        $title = 'Tugas Driver';
        $title_jp = 'ドライバーの任務';
        return view('general_affair.driver.index',
            array(
                'title' => $title,
                'title_jp' => $title_jp,
            )
        )->with('page', 'Driver Report');
    }

    public function indexDriverAttendanceReport()
    {
        $title = 'Kehadiran Driver';
        $title_jp = 'ドライバーの出席';
        return view('general_affair.driver.attendance_report',
            array(
                'title' => $title,
                'title_jp' => $title_jp,
            )
        )->with('page', 'Driver Report');
    }

    public function fetchDriverAttendanceReport(Request $request)
    {
        try {
            $datas = DB::SELECT("SELECT
                CONCAT(
                CASE
                        DAYOFWEEK( datetime ) 
                        WHEN 1 THEN
                        'Minggu' 
                        WHEN 2 THEN
                        'Senin' 
                        WHEN 3 THEN
                        'Selasa' 
                        WHEN 4 THEN
                        'Rabu' 
                        WHEN 5 THEN
                        'Kamis' 
                        WHEN 6 THEN
                        'Jumat' 
                        WHEN 7 THEN
                        'Sabtu' 
                    END,
                    ', ',
                DATE_FORMAT( datetime, '%d %b %Y' )) AS `date`,
                DATE( datetime ) AS dates
            FROM
                `attendances`
            WHERE
                employee_id = '".strtoupper(Auth::user()->username)."'
                AND DATE_FORMAT(datetime, '%Y-%m') = '".date('Y-m')."'
            GROUP BY
                DATE( datetime ),
                CONCAT(
                CASE
                        DAYOFWEEK( datetime ) 
                        WHEN 1 THEN
                        'Minggu' 
                        WHEN 2 THEN
                        'Senin' 
                        WHEN 3 THEN
                        'Selasa' 
                        WHEN 4 THEN
                        'Rabu' 
                        WHEN 5 THEN
                        'Kamis' 
                        WHEN 6 THEN
                        'Jumat' 
                        WHEN 7 THEN
                        'Sabtu' 
                    END,
                    ', ',
                DATE_FORMAT( datetime, '%d %b %Y' ))
            ORDER BY
                DATE( datetime ) DESC");

            $min = DB::SELECT("SELECT
                *
                FROM
                (
                    SELECT
                    'start' AS `status`,
                    DATE(attendances.`datetime`) AS dates,
                    min(attendances.`datetime`) datetimes
                    FROM
                    `attendances`
                    WHERE
                    employee_id = '".strtoupper(Auth::user()->username)."'
                    AND DATE_FORMAT(attendances.`datetime`, '%Y-%m') = '".date('Y-m')."'
                    GROUP BY
                    DATE(attendances.`datetime`)
                ) min
                LEFT JOIN (SELECT latitude, longitude, `datetime` FROM attendances WHERE employee_id = '".strtoupper(Auth::user()->username)."' AND DATE_FORMAT(`datetime`, '%Y-%m') = '".date('Y-m')."') AS latlong ON latlong.`datetime` = min.`datetimes`");
            $max = DB::SELECT("SELECT
                *
                FROM
                (
                    SELECT
                    'end' AS `status`,
                    DATE(attendances.`datetime`) AS dates,
                    max(attendances.`datetime`) datetimes
                    FROM
                    `attendances`
                    WHERE
                    employee_id = '".strtoupper(Auth::user()->username)."'
                    AND DATE_FORMAT(attendances.`datetime`, '%Y-%m') = '".date('Y-m')."'
                    GROUP BY
                    DATE(attendances.`datetime`)
                ) max
                LEFT JOIN (SELECT latitude, longitude, `datetime` FROM attendances WHERE employee_id = '".strtoupper(Auth::user()->username)."' AND DATE_FORMAT(`datetime`, '%Y-%m') = '".date('Y-m')."') AS latlong ON latlong.`datetime` = max.`datetimes`");
            $attendance = [];
            for ($i=0; $i < count($datas); $i++) { 
                $startss = null;
                $endss = null;
                $start_asli = null;
                $end_asli = null;
                $latitude_start = null;
                $longitude_start = null;
                $latitude_end = null;
                $longitude_end = null;

                for ($j=0; $j < count($min); $j++) { 
                    if ($datas[$i]->dates == $min[$j]->dates) {
                        if ($min[$j]->status == 'start') {
                            $startss = date('H:i', strtotime($min[$j]->datetimes));
                            $start_asli = $min[$j]->datetimes;
                            $latitude_start = $min[$j]->latitude;
                            $longitude_start = $min[$j]->longitude;
                        }
                    }
                }

                for ($k=0; $k < count($max); $k++) { 
                    if ($datas[$i]->dates == $max[$k]->dates) {
                        if ($max[$k]->status == 'end') {
                            $endss = date('H:i', strtotime($max[$k]->datetimes));
                            $end_asli = $max[$k]->datetimes;
                            $latitude_end = $max[$k]->latitude;
                            $longitude_end = $max[$k]->longitude;
                        }
                    }
                }

                $attendance[] = array(
                    'date' => $datas[$i]->date,
                    'dates' => $datas[$i]->dates,
                    'startss' => $startss,
                    'endss' => $endss,
                    'start_asli' => $start_asli,
                    'end_asli' => $end_asli,
                    'latitude_start' => $latitude_start,
                    'longitude_start' => $longitude_start,
                    'latitude_end' => $latitude_end,
                    'longitude_end' => $longitude_end
                );
            }
            $response = array(
                'status' => true,
                'attendance' => $attendance,
                'min' => $min,
                'max' => $max
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

    public function fetchDriverJob(Request $request)
    {
        try {
            $driver_job = DB::table('driver_tasks')
            ->select('*',DB::RAW("DATE_FORMAT(date_from,'%d-%b-%Y %H:%i') as froms"),DB::RAW("DATE_FORMAT(date_to,'%d-%b-%Y %H:%i') as tos"))
            ->where('driver_id',Auth::user()->username)
            ->where('deleted_at',null)
            ->where('remark',null)
            ->where('closure_status','driver')
            ->where(function($query) {
                $query->where(DB::RAW("DATE_FORMAT(date_from,'%Y-%m')"),'=',date('Y-m'))
                      ->orWhere(DB::RAW("DATE_FORMAT(date_from,'%Y-%m')"),'=',date('Y-m', strtotime('last month')));
            })
            ->orderby('times')
            ->get();

            $base64id = [];
            for ($i = 0; $i < count($driver_job); ++$i) {
                $base64ids = base64_encode($driver_job[$i]->task_id);
                array_push($base64id, [
                    'id' => $driver_job[$i]->id,
                    'base64id' => $base64ids,
                ]);
            }
            $response = array(
                'status' => true,
                'base64id' => $base64id,
                'driver_job' => $driver_job
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

    public function indexScanQrCodeDriver($qr_code)
    {
        $check = DB::table('qr_code_generators')
            ->where('code',$qr_code)
            ->where(function ($query) {
                $query->where('duty_status', '!=','completed')
                    ->orwhere('duty_status', null);
            });

        if (explode('_', $qr_code)[1] != '') {
            $check = $check->where('driver_id',Auth::user()->username);
        }

        $check = $check->first();


        $jobs = DB::table('qr_code_generators')
        ->where('driver_id',Auth::user()->username)
        ->whereDate('valid_from','<=',date('Y-m-d'))
        ->whereDate('valid_to','>=',date('Y-m-d'))
        ->where(function ($query) {
                $query->where('duty_status', '!=','completed')
                    ->orwhere('duty_status', null);
            })
        ->get();

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
            if ($datas[$i]->licensePlate == explode('_', $qr_code)[1]) {
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

        if ($data_vehicle != null) {
            $ada_data = 'Ada';
        }

        if ($check) {
            $title = 'Driver Pass';
            $title_jp = '??';
            if ($check->duty_status == null) {
                $status = 'Kerjakan Tugas Ke '.$check->destination;
            }else if ($check->duty_status == 'on duty') {
                $status = 'Sampai di '.$check->destination;
            }else if ($check->duty_status == 'back to base') {
                $status = 'Selesai Ke '.$check->destination;
            }
            if (explode('_', $qr_code)[1] == '') {
                $message = '';
            }else{
                $message = '';
            }
            $color = 'green';
            return view('general_affair.driver.index_scan_report',
                array(
                    'title' => $title,
                    'title_jp' => $title_jp,
                    'message' => $message,
                    'qr_code' => $qr_code,
                    'status' => $status,
                    'color' => $color,
                    'jobs' => $jobs,
                    'remark' => $check->remark,
                    'data_vehicle' => $data_vehicle,
                    'data_vehicle_fuel' => $data_vehicle_fuel,
                    'ada_data' => $ada_data
                )
            )->with('page', 'Driver Report');
        }else{
            $title = 'Driver Pass';
            $title_jp = '??';
            $status = 'Tugas Telah Selesai Dikerjakan!';
            $message = '';
            $color = 'red';
            return view('general_affair.driver.index_scan_report',
                array(
                    'title' => $title,
                    'title_jp' => $title_jp,
                    'message' => $message,
                    'qr_code' => $qr_code,
                    'status' => $status,
                    'color' => $color,
                    'jobs' => $jobs,
                    'remark' => '',
                    'data_vehicle' => $data_vehicle,
                    'data_vehicle_fuel' => $data_vehicle_fuel,
                    'ada_data' => $ada_data
                )
            )->with('page', 'Driver Report');
        }
    }

    public function fetchVehicle(Request $request)
    {
        try {
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
                if ($datas[$i]->licensePlate == $request->get('plat_no')) {
                    $id_vehicle = $datas[$i]->id;
                }
            }

            $data_vehicle = null;
            $data_vehicle_fuel = null;
            $ada_data = 'Tidak';

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
            }

            if ($data_vehicle != null) {
                $ada_data = 'Ada';
            }
            $response = array(
                'status' => true,
                'data_vehicle' => $data_vehicle,
                'data_vehicle_fuel' => $data_vehicle_fuel,
                'ada_data' => $ada_data,
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

    public function inputScanQrCodeDriver($qr_code,Request $request)
    {
        try {
            $check = DB::table('qr_code_generators')
            ->where('code',$qr_code)
            ->first();

            if ($check->duty_status == null) {
                $duty_status = 'on duty';
                $update = DB::table('qr_code_generators')
                    ->where('code',$qr_code)
                    ->update([
                        'duty_status' => 'on duty',
                        'duty_from' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'data' => $request->get('latitude').'_'.$request->get('longitude').'_'.$request->get('fuel').'_'.$request->get('odometer')
                    ]);
            }

            if ($check->duty_status == 'on duty') {
                $duty_status = 'back to base';
                $update = DB::table('qr_code_generators')
                    ->where('code',$qr_code)
                    ->update([
                        'duty_status' => 'back to base',
                        'duty_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'data' => $request->get('latitude').'_'.$request->get('longitude').'_'.$request->get('fuel').'_'.$request->get('odometer')
                    ]);
            }

            if ($check->duty_status == 'back to base') {
                if (str_contains($request->get('remark'), 'regular')) {
                    $duty_status = 'completed';
                    $update = DB::table('qr_code_generators')
                        ->where('code',$qr_code)
                        ->update([
                            'duty_status' => null,
                            'duty_from' => null,
                            'duty_to' => null,
                            'duty_at' => null,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'data' => null
                        ]);
                }else{
                    $duty_status = 'completed';
                    $update = DB::table('qr_code_generators')
                        ->where('code',$qr_code)
                        ->update([
                            'duty_status' => 'completed',
                            'duty_to' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'data' => $request->get('latitude').'_'.$request->get('longitude').'_'.$request->get('fuel').'_'.$request->get('odometer')
                        ]);
                }
            }

            // if ($check && $check->used != null) {
            //     $update = DB::table('qr_code_generators')
            //     ->where('code',$request->get('plat_no'))
            //     ->update([
            //         'used_to' => date('Y-m-d H:i:s'),
            //         'updated_at' => date('Y-m-d H:i:s'),
            //         'data' => $request->get('latitude').'_'.$request->get('longitude').'_'.$request->get('fuel').'_'.$request->get('odometer')
            //     ]);
            // }

            // if (explode('_', $qr_code)[1] == '') {
            //     $qr_code = $qr_code.'.'.$request->get('plat_no');
            // }
            // if (!str_contains($request->get('remark'), 'regular')) {
            //     $update = DB::table('qr_code_generators')
            //     ->where('code',$qr_code)
            //     ->update([
            //         'used' => date('Y-m-d H:i:s'),
            //         'updated_at' => date('Y-m-d H:i:s'),
            //         'data' => $request->get('latitude').'_'.$request->get('longitude').'_'.$request->get('fuel').'_'.$request->get('odometer')
            //     ]);
            // }

            $filebase64 = '';
            $tujuan_upload = 'images/driver';
            $fileData_name = '';
            if ($request->file('fileData') != null) {
                $fileData = $request->file('fileData');
                $fileData_file = $fileData->getClientOriginalName();
                $fileData_ext = pathinfo($fileData_file, PATHINFO_EXTENSION);

                $fileData_name = md5('fileData' . date('YmdHis')) . '.' . $fileData_ext;
                $fileData->move($tujuan_upload, $fileData_name);

                $path = public_path('images/driver/').'/'.$fileData_name;
                $filebase64 = base64_encode(file_get_contents($path));
            }

            // $log = DB::table('driver_control_logs')
            // ->insert([
            //     'code' => $qr_code,
            //     'latitude' => $request->get('latitude'),
            //     'longitude' => $request->get('longitude'),
            //     'fuel' => $request->get('fuel'),
            //     'odometer' => $request->get('odometer'),
            //     'duty_status' => $duty_status,
            //     'images' => $filebase64,
            //     'created_by' => '1',
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ]);

            $response = array(
                'status' => true,
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

    public function indexDriverAttendance()
    {
        $title = 'Rekam Kehadiran';
        $title_jp = '出席記録';

        // $empsync = DB::select('select * from employee_syncs where employee_id = "'.Auth::user()->username.'" and end_date is null LIMIT 1');
        // $empsync = DB::table('employee_syncs')->where('employee_id',Auth::user()->username)->where('end_date',null)->limit(1)->get();
        $vehicle = DB::table('driver_lists')
        ->get();

        return view('general_affair.driver.attendance', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'vehicle' => $vehicle,
            // 'empsync' => $empsync
        ))->with('page', 'Attendance');
    }

    function fetchDriverOdometer(Request $request)
    {
        try {
            $nopol = $request->get('nopol');
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
                if ($datas[$i]->licensePlate == $nopol) {
                    $id_vehicle = $datas[$i]->id;
                }
            }

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
            }

            

            $response = array(
                'status' => true,
                'data_vehicle' => $data_vehicle,
                'data_vehicle_fuel' => $data_vehicle_fuel,
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

    public function inputDriverAttendance(Request $request)
    {
        try {
            $employee_id = $request->input('employee_id');
            $name = $request->input('name');
            $department = $request->input('department');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $plat_no = $request->input('plat_no');
            $car = $request->input('car');
            $odometer = $request->input('odometer');
            $fuel = $request->input('fuel');
            $tanggal = date('Y-m-d-H-i-s');

            $_IP_ADDRESS = $_SERVER['REMOTE_ADDR'];

            $_PERINTAH = "arp -a $_IP_ADDRESS";
            ob_start();
            system($_PERINTAH);
            $_HASIL = ob_get_contents();
            ob_clean();
            $_PECAH = strstr($_HASIL, $_IP_ADDRESS);
            
            if ($_PECAH == FALSE) {
                $_HASIL = $_IP_ADDRESS;
            }else{
                $_PECAH_STRING = explode($_IP_ADDRESS, str_replace(" ", "", $_PECAH));
                $_HASIL = substr($_PECAH_STRING[1], 0, 17);               
            }

            $url = "https://locationiq.org/v1/reverse.php?key=pk.456ed0d079b6f646ad4db592aa541ba0&lat=".$latitude."&lon=".$longitude."&format=json";
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            $results = curl_exec($curlHandle);
            curl_close($curlHandle);

            $addrs = json_encode($results);
            $loc2 = explode('\"',$addrs);

            $keyVillage = array_search('village', $loc2);
            $keyResidential = array_search('residential', $loc2);
            $keyHamlet = array_search('hamlet', $loc2);
            $keyNeighbourhood = array_search('neighbourhood', $loc2);

            $keyStateDistrict = array_search('state_district', $loc2);
            $keyCity = array_search('city', $loc2);
            $keyCounty = array_search('county', $loc2);

            $keyState = array_search('state', $loc2);
            $keyPostcode = array_search('postcode', $loc2);
            $keyCountry = array_search('country', $loc2);

            if($keyVillage && $loc2[$keyVillage+2] != ":"){
                $village = $loc2[$keyVillage+2];
            }
            else if($keyResidential && $loc2[$keyResidential+2] != ":") {
                $village = $loc2[$keyResidential+2];
            }
            else if($keyHamlet && $loc2[$keyHamlet+2] != ":") {
                $village = $loc2[$keyHamlet+2];
            }
            else if($keyNeighbourhood && $loc2[$keyNeighbourhood+2] != ":") {
                $village = $loc2[$keyNeighbourhood+2];
            }
            else{  
                $village = null;
            }

            if ($keyStateDistrict && $loc2[$keyStateDistrict + 2] != ":") {
                $city = $loc2[$keyStateDistrict + 2];
            }
            else if($keyCity && $loc2[$keyCity + 2] != ":") {
                $city = $loc2[$keyCity + 2];
            }
            else if($keyCounty && $loc2[$keyCounty+2] != ":") {
                $city = $loc2[$keyCounty+2];
            }
            else{  
                $city = null;
            }

            if($keyState && $loc2[$keyState + 2] != ":"){
                $province = $loc2[$keyState + 2];
            }
            else{
                $province = null;
            }

            $create = DB::table('attendances')
            ->insertGetId([
                'employee_id' => $employee_id,
                'name' => $name,
                'department' => $department,
                'datetime' => $tanggal,
                // 'images' => $file_upload_foto,
                // 'images_odometer' => $file_upload_foto_odometer,
                // 'images_location' => $file_upload_foto_location,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'plat_no' => $plat_no,
                'car' => $car,
                'odometer' => $odometer,
                'fuel' => $fuel,
                'state' => $province,
                'state_district' => $city,
                'village' => $village,
                'ip_address' => $_HASIL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $response = array(
                'status' => true,
                'id' => $create
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

    function inputDriverAttendanceImage1(Request $request) {
        try {
            $id = $request->get('id');

            $tujuan_upload = 'images/absensi';

            $file_foto = $request->get('file_foto');
            $file_foto1 = explode(',', $file_foto)[1];
            $file_foto1 = str_replace(' ', '+', $file_foto1);
            $data = base64_decode($file_foto1);
            $filename_foto = 'Foto Absensi '.$request->input('employee_id').' ('.date('d-M-y H-i-s').')[0].png';
            file_put_contents($tujuan_upload.'/'.$filename_foto, $data);
            $data_foto[]=$filename_foto;
            $file_upload_foto = json_encode($data_foto);

            $update = DB::table('attendances')
            ->where('id',$id)
            ->update([
                'images' => $file_upload_foto,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $response = array(
                'status' => true,
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

    function inputDriverAttendanceImage2(Request $request) {
        try {
            $id = $request->get('id');

            $tujuan_upload = 'images/absensi/odometer';

            $file_foto_odo = $request->get('file_foto_odometer');
            $file_foto_odo1 = explode(',', $file_foto_odo)[1];
            $file_foto_odo1 = str_replace(' ', '+', $file_foto_odo1);
            $data_odo = base64_decode($file_foto_odo1);
            $filename_foto_odo = 'Foto Odometer '.$request->input('employee_id').' ('.date('d-M-y H-i-s').')[0].png';
            file_put_contents($tujuan_upload.'/'.$filename_foto_odo, $data_odo);
            $data_foto_odometer[]=$filename_foto_odo;
            $file_upload_foto_odometer = json_encode($data_foto_odometer);

            $update = DB::table('attendances')
            ->where('id',$id)
            ->update([
                'images_odometer' => $file_upload_foto_odometer,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $response = array(
                'status' => true,
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

    function inputDriverAttendanceImage3(Request $request) {
        try {
            $id = $request->get('id');

            $tujuan_upload = 'images/absensi/location';

            $file_foto_location = $request->get('file_location');
            $file_foto_location1 = explode(',', $file_foto_location)[1];
            $file_foto_location1 = str_replace(' ', '+', $file_foto_location1);
            $data_location = base64_decode($file_foto_location1);
            $filename_foto_location = 'Foto Location '.$request->input('employee_id').' ('.date('d-M-y H-i-s').')[0].png';
            file_put_contents($tujuan_upload.'/'.$filename_foto_location, $data_location);
            $data_foto_location[]=$filename_foto_location;
            $file_upload_foto_location = json_encode($data_foto_location);

            $update = DB::table('attendances')
            ->where('id',$id)
            ->update([
                'images_location' => $file_upload_foto_location,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $response = array(
                'status' => true,
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

    function indexInputDriverJob($id)
    {
        $title = 'Kerjakan Tugas Driver';
        $title_jp = 'ドライバーのタスクを実行する';
        $driver_task = DB::table('driver_tasks')
        ->where('id',$id)
        ->first();

        $car = DB::table('driver_lists')
        ->where('plat_no', $driver_task->plat_no)
        ->first();
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
        //     CURLOPT_HTTPHEADER => array(
        //         'Accept: application/json',
        //         'Content-Type: application/x-www-form-urlencoded',
        //         'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
        //     ),
        // ));
        // $response = curl_exec($curl);

        // curl_close($curl);

        // $datas = json_decode($response)->data;

        $id_vehicle = '';

        // for ($i=0; $i < count($datas); $i++) { 
        //     if ($datas[$i]->licensePlate == $driver_task->plat_no) {
        //         $id_vehicle = $datas[$i]->id;
        //     }
        // }

        $ada_data = 'Tidak';

        $data_vehicle = null;
        $data_vehicle_fuel = null;

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
        //         CURLOPT_HTTPHEADER => array(
        //             'Accept: application/json',
        //             'Content-Type: application/x-www-form-urlencoded',
        //             'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
        //         ),
        //     ));
        //     $response = curl_exec($curl);

        //     curl_close($curl);

        //     $data_vehicle_fuel = json_decode($response)->data;

        //     // $ada_data = 'Ada';
        // }

        $pertamax = DB::table('driver_gasoline_prices')->where('type','Pertamax')->first();
        $dex = DB::table('driver_gasoline_prices')->where('type','Dex')->first();

        $bbm = [
            'Pertamax_'.$pertamax->price,
            'Pertamina Dex_'.$dex->price
        ];
        return view('general_affair.driver.index_task',
            array(
                'title' => $title,
                'title_jp' => $title_jp,
                'id' => $id,
                'data_vehicle' => $data_vehicle,
                'driver_task' => $driver_task,
                'car' => $car,
                'data_vehicle_fuel' => $data_vehicle_fuel,
                'bbm' => $bbm,
                'bbm2' => $bbm,
            )
        )->with('page', 'Driver Report');
    }

    function inputDriverJobImage1(Request $request)
    {
        try {
            $id = $request->get('id');
            $driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->first();

            $tujuan_upload = 'images/driver_task';
            $fileData_name = '';

            $fileData = $request->get('fileData');
            $fileData1 = explode(',', $fileData)[1];
            $fileData1 = str_replace(' ', '+', $fileData1);
            $data = base64_decode($fileData1);
            $fileData_name = 'Bukti Pengisian '.$driver_task->driver_id.' - '.$id.' - '. date('YmdHis') . '.png';
            file_put_contents($tujuan_upload.'/'.$fileData_name, $data);

            $update_driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'fuel_in_evidence' => $fileData_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $response = array(
                'status' => true,
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

    function inputDriverJobImage2(Request $request)
    {
        try {
            $id = $request->get('id');
            $driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->first();

            $tujuan_upload = 'images/driver_task';
            $fileData_name = '';

            $fileDataOdoBefore = $request->get('fileDataOdoBefore');
            $fileDataOdoBefore1 = explode(',', $fileDataOdoBefore)[1];
            $fileDataOdoBefore1 = str_replace(' ', '+', $fileDataOdoBefore1);
            $data = base64_decode($fileDataOdoBefore1);
            $fileDataOdoBefore_name = 'Bukti Odo Before '.$driver_task->driver_id.' - '.$id.' - '. date('YmdHis') . '.png';
            file_put_contents($tujuan_upload.'/'.$fileDataOdoBefore_name, $data);

            $update_driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'odometer_before_evidence' => $fileDataOdoBefore_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $response = array(
                'status' => true,
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

    function inputDriverJobImage3(Request $request)
    {
        try {
            $id = $request->get('id');
            $driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->first();

            $tujuan_upload = 'images/driver_task';
            $fileData_name = '';

            $fileDataOdoAfter = $request->get('fileDataOdoAfter');
            $fileDataOdoAfter1 = explode(',', $fileDataOdoAfter)[1];
            $fileDataOdoAfter1 = str_replace(' ', '+', $fileDataOdoAfter1);
            $data = base64_decode($fileDataOdoAfter1);
            $fileDataOdoAfter_name = 'Bukti Odo After '.$driver_task->driver_id.' - '.$id.' - '. date('YmdHis') . '.png';
            file_put_contents($tujuan_upload.'/'.$fileDataOdoAfter_name, $data);

            $update_driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'odometer_after_evidence' => $fileDataOdoAfter_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $response = array(
                'status' => true,
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

    function inputDriverJob(Request $request)
    {
        try {
            $id = $request->get('id');
            $driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->first();
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $fuel = $request->get('fuel');
            $fuel_actual = $request->get('fuel_actual');
            $fuel_type = $request->get('fuel_type');
            $times = $request->get('times');
            $fuel_amount = $request->get('fuel_amount');
            $fuel_amount_liter = $request->get('fuel_amount_liter');
            $location = $request->get('location');
            $odometer = $request->get('odometer');
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $receipt = $request->get('receipt');

            //GET FUEL NOW
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
            //     CURLOPT_HTTPHEADER => array(
            //         'Accept: application/json',
            //         'Content-Type: application/x-www-form-urlencoded',
            //         'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
            //     ),
            // ));
            // $response = curl_exec($curl);

            // curl_close($curl);

            // $datas = json_decode($response)->data;

            $id_vehicle = '';

            // for ($i=0; $i < count($datas); $i++) { 
            //     if ($datas[$i]->licensePlate == $driver_task->plat_no) {
            //         $id_vehicle = $datas[$i]->id;
            //     }
            // }

            $ada_data = 'Tidak';

            $data_vehicle = null;
            $data_vehicle_fuel = null;

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
            //         CURLOPT_HTTPHEADER => array(
            //             'Accept: application/json',
            //             'Content-Type: application/x-www-form-urlencoded',
            //             'Authorization: Bearer 64JivcpGchQSz2Hjb5Ze5yH1es6l49cY4esam51lyTB9d2jUdBbC8lj2sanbC68d04Na4w5a92AeQC6IQ2eu54b2S6IlaSe5mj8bu2QjFL8aRxe3Cd13eOZ51qzBeq3IEhEs861y235PO6VqK2Sbxzif33fhVJuRB1akQorjN4NeeYL5y1vITCElP6Odi2C148nZe44OV8q2G9zS65h1SlS89ru5N8JRj8f2B35F6hXDzpk4KhJOeS32LF41424e',
            //         ),
            //     ));
            //     $response = curl_exec($curl);

            //     curl_close($curl);

            //     $data_vehicle_fuel = json_decode($response)->data;

            //     // $ada_data = 'Ada';
            // }

            //END GET FUEL NOW

            $fuel_actual_after = 0;

            // if ($data_vehicle_fuel) {
            //     $fuel_actual_after = ($data_vehicle_fuel->fuelFiltered/100)*$data_vehicle_fuel->fuelCapacity;
            // }

            $update_driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'times' => $times,
                'odometer' => $odometer,
                'fuel_type' => $fuel_type,
                'fuel' => $fuel,
                'fuel_actual' => $fuel_actual,
                'fuel_actual_after' => $fuel_actual_after,
                'fuel_amount_liter' => $fuel_amount_liter,
                'fuel_amount' => $fuel_amount,
                'receipt' => $receipt,
                'location' => $location,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $response = array(
                'status' => true,
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

    public function inputDriverJobUrgent(Request $request)
    {
        try {
            $check = DB::table('qr_code_generators')
            ->whereDate('valid_from','<=',date('Y-m-d'))
            ->whereDate('valid_to','>=',date('Y-m-d'))
            ->where('code',$request->get('code'))
            ->first();

            $destination = $request->get('destination');
            $passenger = $request->get('passenger');
            $valid_to_1 = $request->get('valid_to_1');
            $valid_to_2 = $request->get('valid_to_2');
            $valid_from = $request->get('valid_from');

            if ($check) {
                $prefix_now = "D".date('Y') . date('m');
                $code_generator = CodeGenerator::where('note', '=', 'qr_generator')->first();
                if ($prefix_now != $code_generator->prefix) {
                    $code_generator->prefix = $prefix_now;
                    $code_generator->index = '0';
                    $code_generator->save();
                }
                $number = sprintf("%'.0" . $code_generator->length . "d", $code_generator->index + 1);
                $codes = $code_generator->prefix . $number;
                $code_generator->index = $code_generator->index + 1;
                $code_generator->save();

                $path_file = 'qrcode/driver/qrcode'.$codes.'_'.explode('_', $request->get('code'))[1].'.png';
                $code = $codes.'_'.explode('_', $request->get('code'))[1];
                $purpose = 'driver';
                $remark = 'passenger_urgent';
                $driver_id = Auth::user()->username;
                $driver_name = Auth::user()->name;
                $valid_to = date('Y-m-d').' '.sprintf('%02d', $valid_to_1).':'.sprintf('%02d', $valid_to_2).':00';
                $created_by = Auth::user()->id;
                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');

                $input_job = DB::table('qr_code_generators')
                ->insert([
                    'path_file' => $path_file,
                    'purpose' => $purpose,
                    'remark' => $remark.'_'.$request->get('code'),
                    'code' => $code,
                    'driver_id' => $driver_id,
                    'driver_name' => $driver_name,
                    'valid_from' => $valid_from,
                    'valid_to' => $valid_to,
                    'destination' => $destination,
                    'passenger' => $passenger,
                    'created_by' => $created_by,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ]);
                $response = array(
                    'status' => true,
                    'codes' => $code
                );
                return Response::json($response);
            }else{
                $response = array(
                    'status' => false,
                    'message' => 'Invalid QR Code'
                );
                return Response::json($response);
            }
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage()
            );
            return Response::json($response);
        }
    }

    function indexPassengerAttendance() {
        $reguler = [
            'OS0005_Malang',
            'OS0115_Surabaya',
        ];
        $title = 'Absensi Penumpang';
        $title_jp = '乗客の出席';
        $check_attendance = DB::SELECT("SELECT
            GROUP_CONCAT(a.datang) AS datang,
            GROUP_CONCAT(a.status_datang) AS status_datang,
            GROUP_CONCAT(a.pulang) AS pulang,
            GROUP_CONCAT(a.status_pulang) AS status_pulang
            FROM
            (
                (
                SELECT
                    id AS datang,
                    IF(
                    '".date('Y-m-d H:i:s')."' >= CONCAT('".date('Y-m-d')."', ' ', '04:00:00')
                    AND '".date('Y-m-d H:i:s')."' <= CONCAT('".date('Y-m-d')."', ' ', '12:00:00'),
                    'ON',
                    'OFF'
                    ) AS `status_datang`,
                    NULL AS pulang,
                    NULL AS status_pulang
                FROM
                    attendances
                WHERE
                    DATE(created_at) = '".date('Y-m-d')."'
                    AND employee_id = '".Auth::user()->username."'
                    AND created_at <= CONCAT('".date('Y-m-d')."', ' ', '06:30:00')
                ) UNION ALL
                (
                SELECT NULL AS
                    datang,
                    NULL AS status_datang,
                    id AS pulang,
                    IF(
                    '".date('Y-m-d H:i:s')."' >= CONCAT('".date('Y-m-d')."', ' ', '15:30:00')
                    AND '".date('Y-m-d H:i:s')."' <= CONCAT('".date('Y-m-d')."', ' ', '18:30:00'),
                    'ON',
                    'OFF'
                    ) AS `status_pulang`
                FROM
                    attendances
                WHERE
                    DATE(created_at) = '".date('Y-m-d')."'
                    AND employee_id = '".Auth::user()->username."'
                    AND created_at >= CONCAT('".date('Y-m-d')."', ' ', '15:30:00')
                )
            ) a");
        
        $destination = '';
        for ($i=0; $i < count($reguler); $i++) { 
            if(explode('_',$reguler[$i])[0] == Auth::user()->username){
                $destination = explode('_',$reguler[$i])[1];
            }
        }
        $status_check = 'OFF';
        $id = '';
        $timing = '';
        if($check_attendance[0]->datang != null){
            if($check_attendance[0]->status_datang != 'OFF'){
                $status_check = 'ON';
                $id = $check_attendance[0]->datang;
                $timing = 'datang';
            }
        }

        if($check_attendance[0]->pulang != null){
            if($check_attendance[0]->status_pulang != 'OFF'){
                $status_check = 'ON';
                $id = $check_attendance[0]->pulang;
                $timing = 'pulang';
            }
        }
        
        if($destination != '' && $status_check == 'ON'){
            $passenger = DB::table('driver_passengers')
            ->where('destination',$destination)
            ->get();

            $detail_attendance = DB::table('attendances')
            ->where('id',$id)
            ->first();
            return view('general_affair.driver.passenger.attendance',
                array(
                    'title' => $title,
                    'title_jp' => $title_jp,
                    'attendance' => $check_attendance,
                    'destination' => $destination,
                    'detail_attendance' => $detail_attendance,
                    'driver_id' => Auth::user()->username,
                    'driver_name' => Auth::user()->name,
                    'id' => $id,
                    'passenger' => $passenger,
                    'timing' => $timing,
                    'message' => '',
                )
            )->with('page', 'Driver Report');
        }else{
            return view('general_affair.driver.passenger.attendance',
                array(
                    'title' => $title,
                    'title_jp' => $title_jp,
                    'attendance' => $check_attendance,
                    'destination' => $destination,
                    'driver_id' => Auth::user()->username,
                    'driver_name' => Auth::user()->name,
                    'passenger' => null,
                    'timing' => null,
                    'id' => $id,
                    'message' => 'Sesi Absensi Sudah Berakhir / Rekam Kehadiran belum diisi.<br>Isi rekam kehadiran terlebih dahulu sebelum melakukan absensi penumpang.',
                )
            )->with('page', 'Driver Report');
        }
    }

    function fetchPassengerAttendance(Request $request) {
        try {
            $id = $request->get('id');
            $destination = $request->get('destination');
            $passenger_all = DB::table('driver_passengers')
            ->select('driver_passengers.*')
            ->where('driver_passengers.destination',$destination)
            ->get();

            $time_in = DB::SELECT("SELECT
                employee_id,
                `name`,
                DATE_FORMAT(MIN(`timestamps`), '%H:%i') AS time_in
                FROM
                `driver_passenger_attendances`
                WHERE
                destination = '".$destination."'
                AND DATE(driver_timestamps) = '".date('Y-m-d')."'
                GROUP BY
                employee_id,
                `name`");

            $time_out = DB::SELECT("SELECT
                employee_id,
                `name`,
                DATE_FORMAT(MAX(`timestamps`), '%H:%i') AS time_out
                FROM
                `driver_passenger_attendances`
                WHERE
                destination = '".$destination."'
                AND DATE(driver_timestamps) = '".date('Y-m-d')."'
                GROUP BY
                employee_id,
                `name`");
            $response = array(
                'status' => true,
                'passenger_all' => $passenger_all,
                'time_in' => $time_in,
                'time_out' => $time_out
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

    function inputPassengerAttendance(Request $request) {
        try {
            $id = $request->get('id');
            $destination = $request->get('destination');
            $passengers = $request->get('passengers');
            $timing = $request->get('timing');
            $timestamps = $request->get('timestamps');
            $driver_id = $request->get('driver_id');
            $car = $request->get('car');
            $plat_no = $request->get('plat_no');
            $driver_name = $request->get('driver_name');
            $driver_time = $request->get('driver_time');

            DB::table('driver_passenger_attendances')
            ->insert([
                'id_attendance' => $id,
                'destination' => $destination,
                'timing' => $timing,
                'employee_id' => $passengers['employee_id'],
                'name' => $passengers['name'],
                'department' => $passengers['department'],
                'hire_date' => $passengers['hire_date'],
                'grade_code' => $passengers['grade_code'],
                'employment_status' => $passengers['employment_status'],
                'tag' => $passengers['tag'],
                'driver_id' => $driver_id,
                'driver_name' => $driver_name,
                'driver_timestamps' => $driver_time,
                'car' => $car,
                'plat_no' => $plat_no,
                'timestamps' => $timestamps,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $response = array(
                'status' => true,
                'message' => 'Berhasil Melakukan Absensi Penumpang'
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

    function indexDriverTollParking() {
        $title = 'Biaya Tol & Parkir Driver';
        $title_jp = 'ドライバーの通行料と駐車料金';
        return view('general_affair.driver.index_toll_parking',
            array(
                'title' => $title,
                'title_jp' => $title_jp,
                'driver_id' => Auth::user()->username,
            )
        )->with('page', 'Driver Report');
    }

    function fetchDriverTollParking(Request $request) {
        try {

            $date_from = $request->get('date_from');
            $date_to = $request->get('date_to');

            if ($date_from == "") {
                if ($date_to == "") {
                    // Default: 3 bulan terakhir, termasuk bulan berjalan
                    $first = date('Y-m-01');
                    $last = date('Y-m-t');

                    $firstTitle = date('d M Y', strtotime($first));
                    $lastTitle = date('d M Y', strtotime($last));
                } else {
                    // date_from kosong, date_to ada
                    // Ambil 3 bulan ke belakang berdasarkan date_to
                    $first = date('Y-m-01', strtotime($date_to));
                    $last = $date_to;

                    $firstTitle = date('d M Y', strtotime($first));
                    $lastTitle = date('d M Y', strtotime($last));
                }
            } else {
                if ($date_to == "") {
                    // date_from ada, date_to kosong
                    // Sampai akhir bulan, 2 bulan setelah date_from
                    $first = $date_from;
                    $last = date('Y-m-t', strtotime($date_from));

                    $firstTitle = date('d M Y', strtotime($first));
                    $lastTitle = date('d M Y', strtotime($last));
                } else {
                    // Keduanya diisi
                    $first = $date_from;
                    $last = $date_to;

                    $firstTitle = date('d M Y', strtotime($first));
                    $lastTitle = date('d M Y', strtotime($last));
                }
            }

            $whereClause = "AND DATE(date_from) BETWEEN '".$first."' AND '".$last."'";
            $driver_id = $request->get('driver_id');
            $data = DB::select("SELECT
                *
                FROM
                (
                    (
                    SELECT
                        *
                    FROM
                        driver_tasks
                    WHERE
                        remark IS NOT NULL
                        AND closure_status != 'driver'
                        AND closure_status != 'closed'
                        AND etoll IS NULL
                        AND parking IS NULL
                        AND parking_emoney IS NULL
                        AND driver_id = '".$driver_id."'
                        ".$whereClause."
                    ORDER BY
                        date_from DESC
                    ) UNION ALL
                    (
                    SELECT
                        *
                    FROM
                        driver_tasks
                    WHERE
                        remark IS NULL
                        AND driver_id = '".$driver_id."'
                        AND etoll IS NULL
                        AND parking IS NULL
                        AND parking_emoney IS NULL
                        AND (LOWER(destination) LIKE '%pasuruan%' OR LOWER(destination) LIKE '%kebon%')
                        ".$whereClause."
                    ORDER BY
                        date_from DESC
                    )
                ) a
                ORDER BY
                a.date_from DESC");

            $data_closed = DB::select("SELECT
            *
            FROM
            (
                (
                SELECT
                    *
                FROM
                    driver_tasks
                WHERE
                    remark IS NOT NULL
                    AND (closure_status = 'closed' OR closure_status = 'daily_japanese')
                    AND (etoll IS NOT NULL OR parking IS NOT NULL OR parking_emoney IS NOT NULL)
                    AND driver_id = '".$driver_id."'
                    ".$whereClause."
                ORDER BY
                    date_from DESC
                ) UNION ALL
                (
                SELECT
                    *
                FROM
                    driver_tasks
                WHERE
                    remark IS NULL
                    AND driver_id = '".$driver_id."'
                    AND (etoll IS NOT NULL OR parking IS NOT NULL OR parking_emoney IS NOT NULL)
                    AND (LOWER(destination) LIKE '%pasuruan%' OR LOWER(destination) LIKE '%kebon%')
                    ".$whereClause."
                ORDER BY
                    date_from DESC
                )
            ) a
            ORDER BY
            a.date_from DESC");
            $response = array(
                'status' => true,
                'data' => $data,
                'data_closed' => $data_closed,
                'firstTitle' => $firstTitle,
                'lastTitle' => $lastTitle
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

    public function indexRequestFuel()
{
    $title = 'Request Fuel';
    $title_jp = '燃料リクエスト';

    $driver = DB::table('driver_lists')
        ->where('driver_id', auth()->user()->username)
        ->first();

    $car = DB::table('driver_lists')
        ->whereNotNull('plat_no')
        ->select('car', 'plat_no', 'fuel_capacity', 'fuel_consumption', 'fuel_type')
        ->orderBy('car', 'asc')
        ->get();

    $pertamax = DB::table('driver_gasoline_prices')->where('type', 'Pertamax')->first();
    $dex = DB::table('driver_gasoline_prices')->where('type', 'Dex')->first();

    $bbm = array(
        'Pertamax_' . ($pertamax ? $pertamax->price : 0),
        'Pertamina Dex_' . ($dex ? $dex->price : 0)
    );

    $fuelEfficiency3Months = $this->buildFuelEfficiency3Months($car);

    return view('general_affair.driver.index_request_fuel', compact(
        'title',
        'title_jp',
        'driver',
        'car',
        'bbm',
        'fuelEfficiency3Months'
    ));
}

public function buildFuelEfficiency3Months($cars)
{
    $cars = collect($cars)->values();
    $periodStart = date('Y-m-d', strtotime('-3 months'));
    $periodEnd = date('Y-m-d');

    $plateNumbers = $cars->map(function ($car) {
        return isset($car->plat_no) ? trim((string) $car->plat_no) : null;
    })->filter()->unique()->values();

    if ($plateNumbers->isEmpty()) {
        return array();
    }

    /*
     * date_from wajib dipilih. Field times hanya berisi jam, sehingga tidak
     * boleh dipakai sendiri untuk mengurutkan transaksi lintas tanggal.
     */
    $tasks = DB::table('driver_tasks')
        ->whereNull('remark')
        ->whereNotNull('times')
        ->whereNotNull('date_from')
        ->whereNotNull('fuel')
        ->where('fuel', '>', 0)
        ->whereNotNull('odometer')
        ->where('odometer', '>', 0)
        ->whereBetween(DB::raw('DATE(date_from)'), array($periodStart, $periodEnd))
        ->whereIn('plat_no', $plateNumbers->all())
        ->select('id', 'plat_no', 'date_from', 'times', 'fuel', 'odometer')
        ->orderBy('plat_no', 'asc')
        ->orderBy('date_from', 'asc')
        ->orderBy('times', 'asc')
        ->orderBy('id', 'asc')
        ->get();

    $tasks = collect($tasks);
    $results = array();

    foreach ($cars as $carItem) {
        if (!isset($carItem->plat_no)) continue;

        $platNo = trim((string) $carItem->plat_no);
        if ($platNo === '') continue;

        $normalizedPlate = $this->normalizeFuelPlate($platNo);
        $vehicleTasks = $tasks->filter(function ($task) use ($normalizedPlate) {
            return isset($task->plat_no)
                && $this->normalizeFuelPlate($task->plat_no) === $normalizedPlate;
        })->sortBy(function ($task) {
            return strtotime($task->date_from . ' ' . $task->times);
        })->values();

        $summary = $this->calculateEfficiencyFromTasks($vehicleTasks);
        $monthly = $this->calculateMonthlyEfficiency($vehicleTasks);

        $results[] = array(
            'plat_no' => $platNo,
            'vehicle' => isset($carItem->car) ? $carItem->car : null,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'transaction_count' => $vehicleTasks->count(),
            'first_odometer' => $summary['first_odometer'],
            'last_odometer' => $summary['last_odometer'],
            'valid_interval_count' => $summary['valid_interval_count'],
            'total_distance' => $summary['total_distance'],
            'total_fuel' => $summary['total_fuel'],
            'efficiency' => $summary['efficiency'],
            'monthly' => $monthly
        );
    }

    return $results;
}

private function normalizeFuelPlate($value)
{
    return strtoupper(preg_replace('/\s+/', '', trim((string) $value)));
}

private function calculateEfficiencyFromTasks($tasks)
{
    $tasks = collect($tasks)->values();
    $totalDistance = 0.0;
    $totalFuel = 0.0;
    $firstOdometer = null;
    $lastOdometer = null;
    $validIntervalCount = 0;
    $previous = null;

    foreach ($tasks as $task) {
        $currentOdometer = isset($task->odometer) ? (float) $task->odometer : 0;
        $currentFuel = isset($task->fuel) ? (float) $task->fuel : 0;

        if ($currentOdometer <= 0 || $currentFuel <= 0) continue;

        if ($previous === null) {
            $previous = $task;
            $firstOdometer = $currentOdometer;
            $lastOdometer = $currentOdometer;
            continue;
        }

        $distance = $currentOdometer - (float) $previous->odometer;

        /* Abaikan odometer mundur, duplikat, dan lonjakan di atas 5.000 km. */
        if ($distance > 0 && $distance <= 5000) {
            $totalDistance += $distance;
            $totalFuel += $currentFuel;
            $lastOdometer = $currentOdometer;
            $validIntervalCount++;
        }

        $previous = $task;
    }

    return array(
        'first_odometer' => $firstOdometer,
        'last_odometer' => $lastOdometer,
        'valid_interval_count' => $validIntervalCount,
        'total_distance' => round($totalDistance, 2),
        'total_fuel' => round($totalFuel, 2),
        'efficiency' => $totalFuel > 0 ? round($totalDistance / $totalFuel, 2) : 0
    );
}

private function calculateMonthlyEfficiency($tasks)
{
    $tasks = collect($tasks)->values();
    $months = array();
    $previous = null;

    foreach ($tasks as $task) {
        $currentOdometer = isset($task->odometer) ? (float) $task->odometer : 0;
        $currentFuel = isset($task->fuel) ? (float) $task->fuel : 0;

        if ($currentOdometer <= 0 || $currentFuel <= 0 || empty($task->date_from)) continue;

        if ($previous === null) {
            $previous = $task;
            continue;
        }

        $distance = $currentOdometer - (float) $previous->odometer;
        if ($distance <= 0 || $distance > 5000) {
            $previous = $task;
            continue;
        }

        $timestamp = strtotime($task->date_from);
        if ($timestamp === false) {
            $previous = $task;
            continue;
        }

        $monthKey = date('Y-m', $timestamp);
        if (!isset($months[$monthKey])) {
            $months[$monthKey] = array(
                'month' => date('M Y', $timestamp),
                'distance' => 0.0,
                'fuel' => 0.0,
                'efficiency' => 0.0,
                'transaction_count' => 0
            );
        }

        $months[$monthKey]['distance'] += $distance;
        $months[$monthKey]['fuel'] += $currentFuel;
        $months[$monthKey]['transaction_count']++;
        $previous = $task;
    }

    ksort($months);
    foreach ($months as $key => $month) {
        $distance = round($month['distance'], 2);
        $fuel = round($month['fuel'], 2);
        $months[$key]['distance'] = $distance;
        $months[$key]['fuel'] = $fuel;
        $months[$key]['efficiency'] = $fuel > 0 ? round($distance / $fuel, 2) : 0;
    }

    return array_values($months);
}

    public function inputRequestFuel(Request $request)
    {
        try {
            $odometer = $request->get('odometer');
            $remaining_range = $request->get('remaining_range');
            $fuel_location = $request->get('fuel_location');
            $fuel_consumption = $request->get('fuel_consumption');
            $fuel_capacity = $request->get('fuel_capacity');
            $fuel_price_per_liter = $request->get('fuel_price_per_liter');
            $fuel_type = $request->get('fuel_type');
            $voucher_denomination = $request->get('voucher_denomination');
            $voucher_count = $request->get('voucher_count');
            $voucher_total = $request->get('voucher_total');
            $voucher_difference = $request->get('voucher_difference');
            $remaining_fuel_liter = $request->get('remaining_fuel_liter');
            $fuel_to_buy_liter = $request->get('fuel_to_buy_liter');
            $fuel_total_cost = $request->get('fuel_total_cost');
            $driver_id = $request->get('driver_id');
            $driver_name = $request->get('driver_name');
            $date = $request->get('date');
            $vehicle = $request->get('vehicle');
            $plat_no = $request->get('plat_no');

            if (!$request->hasFile('fileDataOdoBefore')) {
                throw new \Exception('Foto odometer sebelum pengisian wajib dilampirkan.');
            }

            $tujuan_upload = public_path('images/driver/request_fuel');
            if (!is_dir($tujuan_upload)) {
                mkdir($tujuan_upload, 0755, true);
            }

            $fileDataOdoBefore = $request->file('fileDataOdoBefore');
            $fileDataOdoBefore_file = $fileDataOdoBefore->getClientOriginalName();
            $fileDataOdoBefore_ext = strtolower(pathinfo($fileDataOdoBefore_file, PATHINFO_EXTENSION));
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'webp');
            if (!in_array($fileDataOdoBefore_ext, $allowedExtensions)) {
                throw new \Exception('Format foto harus JPG, JPEG, PNG, atau WEBP.');
            }

            $fileDataOdoBefore_name = md5('fileDataOdoBefore' . date('YmdHis') . uniqid()) . '.' . $fileDataOdoBefore_ext;
            $fileDataOdoBefore->move($tujuan_upload, $fileDataOdoBefore_name);

            DB::table('driver_fuel_requests')->insert(array(
                'odometer' => $odometer,
                'remaining_range' => $remaining_range,
                'fuel_location' => $fuel_location,
                'fuel_consumption' => $fuel_consumption,
                'fuel_capacity' => $fuel_capacity,
                'fuel_price_per_liter' => $fuel_price_per_liter,
                'fuel_type' => $fuel_type,
                'voucher_denomination' => $voucher_denomination,
                'voucher_count' => $voucher_count,
                'voucher_total' => $voucher_total,
                'voucher_difference' => $voucher_difference,
                'remaining_fuel_liter' => $remaining_fuel_liter,
                'fuel_to_buy_liter' => $fuel_to_buy_liter,
                'fuel_total_cost' => $fuel_total_cost,
                'fileDataOdoBefore' => $fileDataOdoBefore_name,
                'driver_id' => $driver_id,
                'driver_name' => $driver_name,
                'date' => $date,
                'vehicle' => $vehicle,
                'plat_no' => $plat_no,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ));

            $cars = DB::select("SELECT plat_no, LOWER(car) AS cars, fuel_capacity, fuel_consumption FROM driver_lists WHERE fuel_capacity IS NOT NULL ORDER BY LOWER(car)");
            $selectedCar = DB::table('driver_lists')
                ->where('plat_no', $plat_no)
                ->select('car', 'plat_no', 'fuel_capacity', 'fuel_consumption', 'fuel_type')
                ->first();

            /* Ambil ringkasan efisiensi hanya untuk kendaraan yang sedang mengajukan. */
            $efficiencyData = null;
            if ($selectedCar) {
                $efficiencyResult = $this->buildFuelEfficiency3Months(collect(array($selectedCar)));
                if (!empty($efficiencyResult)) {
                    $efficiencyData = $efficiencyResult[0];
                }
            }

            $actualEfficiency = $efficiencyData ? (float) $efficiencyData['efficiency'] : 0;
            $standardEfficiency = (float) $fuel_consumption;
            $totalDistance = $efficiencyData ? (float) $efficiencyData['total_distance'] : 0;
            $totalFuelHistory = $efficiencyData ? (float) $efficiencyData['total_fuel'] : 0;
            $monthlyHistory = $efficiencyData && !empty($efficiencyData['monthly']) ? $efficiencyData['monthly'] : array();
            $efficiencyRatio = ($standardEfficiency > 0 && $actualEfficiency > 0)
                ? ($actualEfficiency / $standardEfficiency) * 100
                : 0;

            if ($efficiencyRatio > 130) {
                $efficiencyStatus = 'Periksa data • hasil melebihi standar';
                $efficiencyStatusBg = '#fef3c7';
                $efficiencyStatusColor = '#92400e';
            } elseif ($efficiencyRatio >= 95) {
                $efficiencyStatus = 'Efisien • ' . number_format($efficiencyRatio, 1, ',', '.') . '% dari standar';
                $efficiencyStatusBg = '#dcfce7';
                $efficiencyStatusColor = '#166534';
            } elseif ($efficiencyRatio >= 85) {
                $efficiencyStatus = 'Perlu dipantau • ' . number_format($efficiencyRatio, 1, ',', '.') . '% dari standar';
                $efficiencyStatusBg = '#fef3c7';
                $efficiencyStatusColor = '#92400e';
            } elseif ($efficiencyRatio > 0) {
                $efficiencyStatus = 'Tidak efisien • ' . number_format($efficiencyRatio, 1, ',', '.') . '% dari standar';
                $efficiencyStatusBg = '#fee2e2';
                $efficiencyStatusColor = '#991b1b';
            } else {
                $efficiencyStatus = 'Data efisiensi belum tersedia';
                $efficiencyStatusBg = '#e2e8f0';
                $efficiencyStatusColor = '#475569';
            }

            $tankCapacity = max(0, (float) $remaining_fuel_liter + (float) $fuel_to_buy_liter);
            if ($tankCapacity <= 0) {
                $tankCapacity = max(0, (float) $fuel_capacity);
            }
            $remainingPercent = $tankCapacity > 0 ? ((float) $remaining_fuel_liter / $tankCapacity) * 100 : 0;
            $buyPercent = $tankCapacity > 0 ? ((float) $fuel_to_buy_liter / $tankCapacity) * 100 : 0;
            $remainingHeight = max(24, round(126 * $remainingPercent / 100));
            $buyHeight = max(24, 126 - $remainingHeight);
            if ($remainingHeight + $buyHeight > 126) {
                $buyHeight = 126 - $remainingHeight;
            }

            $isHiaceVehicle = preg_match('/hiace|hi-ace|hi ace/i', $vehicle);
            $fuelFileUrl = asset('images/driver/request_fuel/' . rawurlencode($fileDataOdoBefore_name));
            $safeFuelFileUrl = htmlspecialchars($fuelFileUrl, ENT_QUOTES, 'UTF-8');
            $safeFuelFileName = htmlspecialchars($fileDataOdoBefore_name, ENT_QUOTES, 'UTF-8');
            $driverControlUrl = 'http://10.109.52.4/mirai/public/index/ga_control/driver';

            $fuelDetails = array(
                'Tanggal' => $date,
                'Driver ID' => $driver_id,
                'Nama Driver' => $driver_name,
                'Kendaraan' => $vehicle,
                'Nomor Polisi' => $plat_no,
                'Odometer' => number_format((float) $odometer, 0, ',', '.') . ' Km',
                'Sisa Jarak Tempuh' => '<span style="color:#dc2626;font-weight:bold;">' . number_format((float) $remaining_range, 0, ',', '.') . ' Km</span>',
                'Lokasi Pengisian' => $fuel_location,
                'Konsumsi BBM' => number_format((float) $fuel_consumption, 2, ',', '.') . ' Km/Liter',
                'Kapasitas BBM' => number_format((float) $fuel_capacity, 2, ',', '.') . ' Liter',
                'Harga BBM' => 'Rp ' . number_format((float) $fuel_price_per_liter, 0, ',', '.') . ' /Liter',
                'Jenis BBM' => $fuel_type,
                'Denominasi Voucher' => 'Rp ' . number_format((float) $voucher_denomination, 0, ',', '.'),
                'Jumlah Voucher' => '<span style="color:#dc2626;font-weight:bold;">' . number_format((float) $voucher_count, 0, ',', '.') . ' Lembar</span>',
                'Total Voucher' => '<span style="color:#dc2626;font-weight:bold;">Rp ' . number_format((float) $voucher_total, 0, ',', '.') . '</span>',
                'Sisa BBM' => '<b>' . number_format((float) $remaining_fuel_liter, 2, ',', '.') . ' Liter</b>',
                'BBM yang Dibeli' => '<b>' . number_format((float) $fuel_to_buy_liter, 2, ',', '.') . ' Liter</b>',
                'Total Biaya BBM' => '<b>Rp ' . number_format((float) $fuel_total_cost, 0, ',', '.') . '</b>'
            );

            $bodyHtml = '<!DOCTYPE html><html lang="id"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
            $bodyHtml .= '<meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Request Pengisian BBM</title></head>';
            $bodyHtml .= '<body style="margin:0;padding:18px;background-color:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#334155;">';
            $bodyHtml .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;max-width:720px;margin:0 auto;background:#ffffff;border:1px solid #dbe3ee;border-collapse:collapse;">';
            $bodyHtml .= '<tr><td style="padding:20px 24px;background:#3447a5;color:#ffffff;font-size:22px;font-weight:bold;">Request Pengisian BBM</td></tr>';
            $bodyHtml .= '<tr><td style="padding:18px 24px 10px;font-size:14px;line-height:22px;">Terdapat request pengisian BBM dengan informasi berikut.</td></tr>';

            /* Simulasi kondisi BBM dan efisiensi. Tabel dipakai agar kompatibel dengan Outlook. */
            $bodyHtml .= '<tr><td style="padding:6px 24px 18px;">';
            $bodyHtml .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:separate;border-spacing:0;background:#f8fbff;border:1px solid #cbd5e1;border-radius:12px;">';
            $bodyHtml .= '<tr><td width="31%" valign="middle" align="center" style="padding:14px 8px;border-right:1px solid #e2e8f0;">';
            $bodyHtml .= '<div style="font-size:13px;font-weight:bold;color:#0f172a;margin-bottom:7px;">Total ' . number_format($tankCapacity, 2, ',', '.') . ' L</div>';
            $bodyHtml .= '<table role="presentation" width="74" cellpadding="0" cellspacing="0" style="width:74px;height:136px;border:3px solid #38536b;border-collapse:separate;border-spacing:3px;background:#ffffff;border-radius:5px;">';
            $bodyHtml .= '<tr><td valign="middle" align="center" height="' . $buyHeight . '" style="height:' . $buyHeight . 'px;background:#f36c2c;color:#ffffff;font-size:12px;font-weight:bold;">+' . number_format((float) $fuel_to_buy_liter, 2, ',', '.') . '</td></tr>';
            $bodyHtml .= '<tr><td valign="middle" align="center" height="' . $remainingHeight . '" style="height:' . $remainingHeight . 'px;background:#1f6f8b;color:#ffffff;font-size:12px;font-weight:bold;">' . number_format((float) $remaining_fuel_liter, 2, ',', '.') . '</td></tr>';
            $bodyHtml .= '</table>';
            $bodyHtml .= '<div style="margin-top:7px;color:#e84f18;font-size:11px;font-weight:bold;">Isi +' . number_format((float) $fuel_to_buy_liter, 2, ',', '.') . ' L</div>';
            $bodyHtml .= '<div style="color:#006ba6;font-size:11px;font-weight:bold;">Sisa ' . number_format((float) $remaining_fuel_liter, 2, ',', '.') . ' L</div>';
            $bodyHtml .= '<div style="margin-top:7px;color:#334155;font-size:11px;font-weight:bold;">ODO ' . number_format((float) $odometer, 0, ',', '.') . ' KM</div>';
            $bodyHtml .= '</td>';

            $bodyHtml .= '<td width="69%" valign="top" style="padding:12px 10px;">';
            $bodyHtml .= '<div style="font-size:14px;font-weight:bold;color:#0f172a;margin-bottom:7px;">⌁ Efisiensi 3 Bulan Terakhir</div>';
            $bodyHtml .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="5" style="border-collapse:separate;">';
            $bodyHtml .= '<tr>';
            $bodyHtml .= '<td width="50%" valign="top" style="padding:9px;border:1px solid #dbe3ee;border-radius:9px;background:#ffffff;"><div style="font-size:10px;color:#64748b;font-weight:bold;">EFISIENSI AKTUAL</div><div style="margin-top:6px;font-size:14px;color:#312e70;font-weight:bold;">' . ($actualEfficiency > 0 ? number_format($actualEfficiency, 2, ',', '.') . ' KM/L' : '-') . '</div></td>';
            $bodyHtml .= '<td width="50%" valign="top" style="padding:9px;border:1px solid #dbe3ee;border-radius:9px;background:#ffffff;"><div style="font-size:10px;color:#64748b;font-weight:bold;">STANDAR KENDARAAN</div><div style="margin-top:6px;font-size:14px;color:#312e70;font-weight:bold;">' . number_format($standardEfficiency, 2, ',', '.') . ' KM/L</div></td>';
            $bodyHtml .= '</tr><tr>';
            $bodyHtml .= '<td width="50%" valign="top" style="padding:9px;border:1px solid #dbe3ee;border-radius:9px;background:#ffffff;"><div style="font-size:10px;color:#64748b;font-weight:bold;">TOTAL JARAK</div><div style="margin-top:6px;font-size:14px;color:#312e70;font-weight:bold;">' . number_format($totalDistance, 0, ',', '.') . ' KM</div></td>';
            $bodyHtml .= '<td width="50%" valign="top" style="padding:9px;border:1px solid #dbe3ee;border-radius:9px;background:#ffffff;"><div style="font-size:10px;color:#64748b;font-weight:bold;">TOTAL BBM</div><div style="margin-top:6px;font-size:14px;color:#312e70;font-weight:bold;">' . number_format($totalFuelHistory, 2, ',', '.') . ' L</div></td>';
            $bodyHtml .= '</tr></table>';
            $bodyHtml .= '<div style="margin:4px 5px 7px;padding:6px 10px;border-radius:15px;background:' . $efficiencyStatusBg . ';color:' . $efficiencyStatusColor . ';font-size:11px;font-weight:bold;">' . $efficiencyStatus . '</div>';
            $bodyHtml .= '<div style="margin:0 5px 5px;padding:7px 9px;border:1px solid #cbd5e1;border-radius:8px;background:#f8fafc;color:#0f172a;font-size:12px;font-weight:bold;">↻ Riwayat 3 Bulan</div>';

            if (!empty($monthlyHistory)) {
                foreach ($monthlyHistory as $monthData) {
                    $monthName = isset($monthData['month']) ? $monthData['month'] : '-';
                    $monthDistance = isset($monthData['distance']) ? (float) $monthData['distance'] : 0;
                    $monthFuel = isset($monthData['fuel']) ? (float) $monthData['fuel'] : 0;
                    $monthEfficiency = isset($monthData['efficiency']) ? (float) $monthData['efficiency'] : 0;
                    $bodyHtml .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 5px 5px;border:1px solid #dbe3ee;border-radius:8px;background:#ffffff;">';
                    $bodyHtml .= '<tr><td colspan="2" style="padding:7px 8px 3px;color:#312e70;font-size:11px;font-weight:bold;">' . htmlspecialchars($monthName, ENT_QUOTES, 'UTF-8') . '</td></tr>';
                    $bodyHtml .= '<tr><td width="50%" style="padding:2px 8px 7px;color:#334155;font-size:11px;">' . number_format($monthDistance, 0, ',', '.') . ' KM<br>' . number_format($monthFuel, 2, ',', '.') . ' L</td>';
                    $bodyHtml .= '<td width="50%" align="right" valign="bottom" style="padding:2px 8px 7px;color:#334155;font-size:11px;">' . number_format($monthEfficiency, 2, ',', '.') . ' KM/L</td></tr></table>';
                }
            } else {
                $bodyHtml .= '<div style="margin:0 5px;padding:8px;border:1px solid #dbe3ee;border-radius:8px;background:#ffffff;color:#64748b;font-size:11px;">Rincian bulanan belum tersedia.</div>';
            }
            $bodyHtml .= '</td></tr></table></td></tr>';

            $bodyHtml .= '<tr><td style="padding:0 24px 20px;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">';
            foreach ($fuelDetails as $label => $value) {
                if ($label == 'Sisa Jarak Tempuh') {
                    $bodyHtml .= '<tr><th align="left" style="padding:9px 10px;border:1px solid #dbe3ee;background:#eef2ff;color:#3447a5;font-size:13px;">Foto Odometer Sebelum</th>';
                    $bodyHtml .= '<td style="padding:9px 10px;border:1px solid #dbe3ee;font-size:13px;"><div style="margin-bottom:8px;"><img src="' . $safeFuelFileUrl . '" alt="Foto Odometer Sebelum" style="max-width:100%;width:320px;height:auto;border:1px solid #cbd5e1;border-radius:4px;display:block;"></div><div><a href="' . $safeFuelFileUrl . '" style="color:#4056b5;text-decoration:underline;">Preview File (' . $safeFuelFileName . ')</a></div></td></tr>';
                    if ($isHiaceVehicle) {
                        $hiaceImageUrl = htmlspecialchars(asset('images/Hiace.png'), ENT_QUOTES, 'UTF-8');
                        $bodyHtml .= '<tr><th align="left" style="padding:9px 10px;border:1px solid #dbe3ee;background:#eef2ff;color:#3447a5;font-size:13px;">Panduan Pengecekan Hiace</th>';
                        $bodyHtml .= '<td style="padding:9px 10px;border:1px solid #dbe3ee;font-size:13px;"><img src="' . $hiaceImageUrl . '" alt="Panduan Pengecekan Hiace" style="max-width:100%;width:320px;height:auto;border:1px solid #cbd5e1;border-radius:4px;display:block;"></td></tr>';
                    }
                }
                $bodyHtml .= '<tr><th align="left" width="38%" style="padding:9px 10px;border:1px solid #dbe3ee;background:#eef2ff;color:#3447a5;font-size:13px;">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</th>';
                $bodyHtml .= '<td style="padding:9px 10px;border:1px solid #dbe3ee;font-size:13px;">' . $value . '</td></tr>';
            }

            $bodyHtml .= '<tr><th align="left" style="padding:9px 10px;border:1px solid #dbe3ee;background:#eef2ff;color:#3447a5;font-size:13px;">Cek Penugasan Driver</th>';
            $bodyHtml .= '<td style="padding:9px 10px;border:1px solid #dbe3ee;font-size:13px;"><a href="' . htmlspecialchars($driverControlUrl, ENT_QUOTES, 'UTF-8') . '" style="color:#4056b5;text-decoration:underline;">Buka halaman penugasan driver</a></td></tr>';

            $bodyHtml .= '<tr><th align="left" style="padding:9px 10px;border:1px solid #dbe3ee;background:#eef2ff;color:#3447a5;font-size:13px;">Data Kendaraan</th><td style="padding:9px 10px;border:1px solid #dbe3ee;font-size:13px;">';
            $bodyHtml .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">';
            $bodyHtml .= '<tr><th align="left" style="padding:6px 8px;border:1px solid #dbe3ee;background:#f8fafc;color:#3447a5;font-size:12px;">Nomor Polisi</th><th align="left" style="padding:6px 8px;border:1px solid #dbe3ee;background:#f8fafc;color:#3447a5;font-size:12px;">Kendaraan</th><th align="left" style="padding:6px 8px;border:1px solid #dbe3ee;background:#f8fafc;color:#3447a5;font-size:12px;">Kapasitas BBM</th><th align="left" style="padding:6px 8px;border:1px solid #dbe3ee;background:#f8fafc;color:#3447a5;font-size:12px;">Konsumsi BBM</th></tr>';
            if (!empty($cars)) {
                foreach ($cars as $carData) {
                    $bodyHtml .= '<tr><td style="padding:6px 8px;border:1px solid #dbe3ee;font-size:12px;">' . htmlspecialchars($carData->plat_no ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
                    $bodyHtml .= '<td style="padding:6px 8px;border:1px solid #dbe3ee;font-size:12px;">' . htmlspecialchars(ucwords(strtolower($carData->cars ?? '')), ENT_QUOTES, 'UTF-8') . '</td>';
                    $bodyHtml .= '<td style="padding:6px 8px;border:1px solid #dbe3ee;font-size:12px;">' . (isset($carData->fuel_capacity) ? number_format((float) $carData->fuel_capacity, 2, ',', '.') . ' Liter' : '-') . '</td>';
                    $bodyHtml .= '<td style="padding:6px 8px;border:1px solid #dbe3ee;font-size:12px;">' . (isset($carData->fuel_consumption) ? number_format((float) $carData->fuel_consumption, 2, ',', '.') . ' Km/Liter' : '-') . '</td></tr>';
                }
            } else {
                $bodyHtml .= '<tr><td colspan="4" style="padding:6px 8px;border:1px solid #dbe3ee;font-size:12px;">Tidak ada data kendaraan.</td></tr>';
            }
            $bodyHtml .= '</table></td></tr></table></td></tr>';
            $bodyHtml .= '<tr><td style="padding:0 24px 18px;font-size:13px;line-height:20px;color:#475569;">Data ini akan muncul maksimal 5 menit di tampilan MIRAI. Mohon ditunggu terlebih dahulu.</td></tr>';
            $bodyHtml .= '<tr><td style="padding:14px 24px;border-top:1px solid #e2e8f0;background:#f8fafc;color:#64748b;font-size:11px;">Email ini dikirim otomatis.</td></tr>';
            $bodyHtml .= '</table></body></html>';

            Mail::html($bodyHtml, function ($message) {
                $message->from('mis@ympi.co.id', 'PT. Yamaha Musical Products Indonesia');
                $message->to('wachid.hasyim@music.yamaha.com');
                $message->cc(array(
                    'widura@music.yamaha.com',
                    'maruli.sapta.adi@music.yamaha.com'
                ));
                $message->bcc(array('ympi-mis-ML@music.yamaha.com'));
                $message->subject('Request Pengisian BBM');
            });

            return Response::json(array(
                'status' => true,
                'message' => 'Request fuel input successful'
            ));
        } catch (\Exception $e) {
            return Response::json(array(
                'status' => false,
                'message' => $e->getMessage() . ' Line ' . $e->getLine()
            ));
        }
    }

    function indexRequestEMoney() {
        return view('general_affair.driver.index_request_emoney');
    }
}