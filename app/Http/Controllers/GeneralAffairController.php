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
        $title_jp = '??';
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
        $title_jp = '??';
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
            $attendance = DB::select("SELECT
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
                DATE_FORMAT( datetime, '%d %b %Y' )) AS date,
                DATE_FORMAT( startss.datetimes, '%H:%i' ) AS startss,
                DATE_FORMAT( endss.datetimes, '%H:%i' ) AS endss,
                startss.latitude AS latitude_start,
                startss.longitude AS longitude_start,
                endss.latitude AS latitude_end,
                endss.longitude AS longitude_end 
            FROM
                `attendances`
                LEFT JOIN (
                SELECT
                    DATE( attendances.datetime ) AS dates,
                    min( attendances.datetime ) datetimes,
                    latlong.latitude,
                    latlong.longitude
                FROM
                    `attendances` 
                    left join (select latitude, longitude,datetime from attendances where employee_id = '".strtoupper(Auth::user()->username)."' ) as latlong on latlong.datetime = attendances.datetime
                WHERE
                    employee_id = '".strtoupper(Auth::user()->username)."' 
                GROUP BY
                    DATE( attendances.datetime )
                ) AS startss ON startss.dates = DATE( datetime )
                LEFT JOIN (
                SELECT
                    DATE( attendances.datetime ) AS dates,
                    max( attendances.datetime ) datetimes,
                    latlong.latitude,
                    latlong.longitude
                FROM
                    `attendances` 
                    left join (select latitude, longitude,datetime from attendances where employee_id = '".strtoupper(Auth::user()->username)."' ) as latlong on latlong.datetime = attendances.datetime
                WHERE
                    employee_id = '".strtoupper(Auth::user()->username)."' 
                GROUP BY
                    DATE( attendances.datetime )
                ) AS endss ON endss.dates = DATE( datetime ) 
            WHERE
                employee_id = '".strtoupper(Auth::user()->username)."' 
            GROUP BY
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
                DATE_FORMAT( datetime, '%d %b %Y' )),
                startss.datetimes,
                endss.datetimes,
                startss.latitude,
                startss.longitude,
                endss.latitude,
                endss.longitude 
            ORDER BY
                datetime DESC");
            $response = array(
                'status' => true,
                'attendance' => $attendance
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
            // ->where(function ($query) {
            //     $query->where('duty_status', '!=','completed')
            //         ->orwhere('duty_status', null);
            // })
            ->where('driver_id',Auth::user()->username)
            ->get();
            $response = array(
                'status' => true,
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
            // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
            // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
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
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
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
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
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
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
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
                    // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                    // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
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
                    // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                    // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
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
        $title_jp = '';

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

            $tujuan_upload = 'images/absensi';

            for ($i=0; $i < count($request->file('file_foto')); $i++) { 
              $file_foto = $request->file('file_foto')[$i];
              $nama_foto = $file_foto->getClientOriginalName();
              $extension_foto = pathinfo($nama_foto, PATHINFO_EXTENSION);
              $filename_foto = 'Foto Absensi '.$request->input('employee_id').' ('.date('d-M-y H-i-s').')['.$i.'].'.$extension_foto;
              $file_foto->move($tujuan_upload,$filename_foto);
              $data_foto[]=$filename_foto;      
            }
            $file_upload_foto = json_encode($data_foto);   

            $tujuan_upload = 'images/absensi/odometer';

            for ($i=0; $i < count($request->file('file_foto_odometer')); $i++) { 
              $file_foto_odo = $request->file('file_foto_odometer')[$i];
              $nama_foto_odo = $file_foto_odo->getClientOriginalName();
              $extension_foto_odo = pathinfo($nama_foto_odo, PATHINFO_EXTENSION);
              $filename_foto_odo = 'Foto Odometer '.$request->input('employee_id').' ('.date('d-M-y H-i-s').')['.$i.'].'.$extension_foto_odo;
              $file_foto_odo->move($tujuan_upload,$filename_foto_odo);
              $data_foto_odometer[]=$filename_foto_odo;      
            }
            $file_upload_foto_odometer = json_encode($data_foto_odometer);

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
            ->insert([
                'employee_id' => $employee_id,
                'name' => $name,
                'department' => $department,
                'datetime' => $tanggal,
                'images' => $file_upload_foto,
                'images_odometer' => $file_upload_foto_odometer,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'plat_no' => $plat_no,
                'car' => $car,
                'state' => $province,
                'state_district' => $city,
                'village' => $village,
                'ip_address' => $_HASIL,
                'created_at' => date('Y-m-d H:i:s'),
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
        $title_jp = '??';
        $driver_task = DB::table('driver_tasks')
        ->where('id',$id)
        ->first();
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
            // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
            // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
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
            if ($datas[$i]->licensePlate == $driver_task->plat_no) {
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
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
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
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message='.$message.'&type=image&file_name=qrcode123.png&file_url='.$file_url,
                // CURLOPT_POSTFIELDS => 'receiver=6282334197238&device=6281130561777&message=REMINDER!!!%0A%0AMembuat%20Schedule%20Chorei%20MIS%20Bulanan.&type=image&file_name=qrcode123.png&file_url=https%3A%2F%2Fwonder-day.com%2Fwp-content%2Fuploads%2F2020%2F10%2Fwonder-day-among-us-21.png',
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
        return view('general_affair.driver.index_task',
            array(
                'title' => $title,
                'title_jp' => $title_jp,
                'id' => $id,
                'data_vehicle' => $data_vehicle,
                'driver_task' => $driver_task,
                'data_vehicle_fuel' => $data_vehicle_fuel,
            )
        )->with('page', 'Driver Report');
    }

    function inputDriverJob($id,Request $request)
    {
        try {
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

            $tujuan_upload = 'images/driver_task';
            $fileData_name = '';

            if ($request->file('fileData') != null) {
                $fileData = $request->file('fileData');
                $fileData_file = $fileData->getClientOriginalName();
                $fileData_ext = pathinfo($fileData_file, PATHINFO_EXTENSION);

                $fileData_name = 'Bukti Pengisian '.$driver_task->driver_id.' - '.$id.' - '. date('YmdHis') . '.' . $fileData_ext;
                $fileData->move($tujuan_upload, $fileData_name);

                $path = public_path('images/driver_task/').'/'.$fileData_name;
                $filebase64 = 'data:image/' . $fileData_ext . ';base64,' .base64_encode(file_get_contents($path));
                $unlink = unlink('images/driver_task'.'/'.$fileData_name);
            }

            if ($request->file('fileDataOdoBefore') != null) {
                $fileDataOdoBefore = $request->file('fileDataOdoBefore');
                $fileDataOdoBefore_file = $fileDataOdoBefore->getClientOriginalName();
                $fileDataOdoBefore_ext = pathinfo($fileDataOdoBefore_file, PATHINFO_EXTENSION);

                $fileDataOdoBefore_name = 'Bukti Odo Before '.$driver_task->driver_id.' - '.$id.' - '. date('YmdHis') . '.' . $fileDataOdoBefore_ext;
                $fileDataOdoBefore->move($tujuan_upload, $fileDataOdoBefore_name);

                $path = public_path('images/driver_task/').'/'.$fileDataOdoBefore_name;
                $filebase64OdoBefore = 'data:image/' . $fileDataOdoBefore_ext . ';base64,' .base64_encode(file_get_contents($path));
                $unlink = unlink('images/driver_task'.'/'.$fileDataOdoBefore_name);
            }

            if ($request->file('fileDataOdoAfter') != null) {
                $fileDataOdoAfter = $request->file('fileDataOdoAfter');
                $fileDataOdoAfter_file = $fileDataOdoAfter->getClientOriginalName();
                $fileDataOdoAfter_ext = pathinfo($fileDataOdoAfter_file, PATHINFO_EXTENSION);

                $fileDataOdoAfter_name = 'Bukti Odo After '.$driver_task->driver_id.' - '.$id.' - '. date('YmdHis') . '.' . $fileDataOdoAfter_ext;
                $fileDataOdoAfter->move($tujuan_upload, $fileDataOdoAfter_name);

                $path = public_path('images/driver_task/').'/'.$fileDataOdoAfter_name;
                $filebase64OdoAfter = 'data:image/' . $fileDataOdoAfter_ext . ';base64,' .base64_encode(file_get_contents($path));
                $unlink = unlink('images/driver_task'.'/'.$fileDataOdoAfter_name);
            }

            $update_driver_task = DB::table('driver_tasks')
            ->where('id',$id)
            ->update([
                'times' => $times,
                'odometer' => $odometer,
                'fuel_type' => $fuel_type,
                'fuel' => $fuel,
                'fuel_actual' => $fuel_actual,
                'fuel_amount_liter' => $fuel_amount_liter,
                'fuel_amount' => $fuel_amount,
                'location' => $location,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'fuel_in_evidence' => $filebase64,
                'odometer_before_evidence' => $filebase64OdoBefore,
                'odometer_after_evidence' => $filebase64OdoAfter,
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
}