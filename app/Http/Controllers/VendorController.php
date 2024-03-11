<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Response;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\GuestLog;
use App\Models\VendorLog;
use App\Models\CmsVendor;
use App\Models\WposLog;

class VendorController extends Controller
{
    
    public function guest_assessment(){
        return view('kuisioner_guest');
    }

    public function inputGuestAssessment(Request $request)
    {
        try {
            $tujuan_upload = 'files/gsa';

            $quiz = $request->input('question');
            $answer = $request->input('answer');
            $file_vaksin = $request->file('file_vaksin');
            $file_rapid = $request->file('file_rapid');
            $file_pcr = $request->file('file_pcr');

            if ($file_pcr != NULL) {
                $nama = $file_pcr->getClientOriginalName();
                $filename = pathinfo($nama, PATHINFO_FILENAME);
                $extension = pathinfo($nama, PATHINFO_EXTENSION);
                $filename = md5($filename.date('YmdHisa')).'.'.$extension;
                $file_pcr->move($tujuan_upload,$filename);
            }
            else if ($file_vaksin != NULL) {
                $nama = $file_vaksin->getClientOriginalName();
                $filename = pathinfo($nama, PATHINFO_FILENAME);
                $extension = pathinfo($nama, PATHINFO_EXTENSION);
                $filename = md5($filename.date('YmdHisa')).'.'.$extension;
                $file_vaksin->move($tujuan_upload,$filename);
            }
            else if ($file_rapid != NULL) {
                $nama = $file_rapid->getClientOriginalName();
                $filename = pathinfo($nama, PATHINFO_FILENAME);
                $extension = pathinfo($nama, PATHINFO_EXTENSION);
                $filename = md5($filename.date('YmdHisa')).'.'.$extension;
                $file_rapid->move($tujuan_upload,$filename);
            }
            else{
                $filename = NULL;
            }

            $forms = GuestLog::create([
                'tanggal' => date('Y-m-d'),
                'name' => $request->input('name'),
                'company' => $request->input('company'),
                'phone' => $request->input('phone'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
                'reason' => $request->input('reason'),
                'pic' => $request->input('pic'),
                'location' => $request->input('location'),
                'vaksin' => $request->input('vaksin'),
                'question' => $quiz,
                'answer' => $answer,
                'file' => $filename
            ]);

            $forms->save();    

            $isimail = "select * from guest_logs where id = ".$forms->id;
            $mail = db::select($isimail);

            Mail::to(['widura@music.yamaha.com'])->cc('prawoto@music.yamaha.com')->bcc(['rio.irvansyah@music.yamaha.com','mokhamad.khamdan.khabibi@music.yamaha.com'])->send(new SendEmail($mail, 'guest'));

            // Mail::to(['rio.irvansyah@music.yamaha.com'])->bcc(['mokhamad.khamdan.khabibi@music.yamaha.com'])->send(new SendEmail($mail, 'guest'));

            $response = array(
                'status' => true,
                'datas' => 'Berhasil Input Data'
            );
            return Response::json($response);

        } catch (QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                $response = array(
                    'status' => false,
                    'datas' => 'Anda Sudah Mengisi Ini'
                );
                return Response::json($response);
            }
            else{
                $response = array(
                    'status' => false,
                    'datas' => $e->getMessage()
                );
                return Response::json($response);
            }
        }
    }


    public function vendor_assessment(){
        return view('kuisioner_vendor');
    }

    public function inputVendorAssessment(Request $request)
    {
        try {
            $tujuan_upload = 'files/vendor';

            $file_vaksin = $request->file('file_vaksin');
            $file_rapid = $request->file('file_rapid');

            if ($file_rapid != NULL) {
                $nama = $file_rapid->getClientOriginalName();
                $filename = pathinfo($nama, PATHINFO_FILENAME);
                $extension = pathinfo($nama, PATHINFO_EXTENSION);
                $filename = md5($filename.date('YmdHisa')).'.'.$extension;
                $file_rapid->move($tujuan_upload,$filename);
            }
            else{
                $filename = NULL;
            }

            $forms = VendorLog::create([
                'tanggal' => date('Y-m-d'),
                'name' => $request->input('name'),
                'company' => $request->input('company'),
                'result' => $request->input('status'),
                'file' => $filename
            ]);

            $forms->save();                  

            $isimail = "select * from vendor_logs where id = ".$forms->id;
            $mail = db::select($isimail);

            // Mail::to(['mokhamad.khamdan.khabibi@music.yamaha.com'])->bcc(['rio.irvansyah@music.yamaha.com'])->send(new SendEmail($mail, 'vendor'));

            Mail::to(['dicky.kurniawan@music.yamaha.com'])->cc('prawoto@music.yamaha.com')->bcc(['rio.irvansyah@music.yamaha.com','mokhamad.khamdan.khabibi@music.yamaha.com'])->send(new SendEmail($mail, 'vendor'));

            $response = array(
                'status' => true,
                'datas' => 'Berhasil Input Data'
            );
            return Response::json($response);

        } catch (QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                $response = array(
                    'status' => false,
                    'datas' => 'Anda Sudah Mengisi Ini'
                );
                return Response::json($response);
            }
            else{
                $response = array(
                    'status' => false,
                    'datas' => $e->getMessage()
                );
                return Response::json($response);
            }
        }
    }

    public function wpos(){
        return view('kuisioner_wpos');
    }

    public function inputWpos(Request $request)
    {
        try {
            $type = null;
            if ($request->input('type') == 'null' || $request->input('type') == '' || $request->input('type') == null) {
                $type = null;
            }else{
                $type = $request->input('type');
            }

            $location = null;
            if ($request->input('location') == 'null' || $request->input('location') == '' || $request->input('location') == null) {
                $location = null;
            }else{
                $location = $request->input('location');
            }

            $question1 = null;
            if ($request->input('question1') == 'null' || $request->input('question1') == '' || $request->input('question1') == null) {
                $question1 = null;
            }else{
                $question1 = $request->input('question1');
            }

            $question2 = null;
            if ($request->input('question2') == 'null' || $request->input('question2') == '' || $request->input('question2') == null) {
                $question2 = null;
            }else{
                $question2 = $request->input('question2');
            }

            $question3 = null;
            if ($request->input('question3') == 'null' || $request->input('question3') == '' || $request->input('question3') == null) {
                $question3 = null;
            }else{
                $question3 = $request->input('question3');
            }

            $question4 = null;
            if ($request->input('question4') == 'null' || $request->input('question4') == '' || $request->input('question4') == null) {
                $question4 = null;
            }else{
                $question4 = $request->input('question4');
            }


            $forms = DB::table('wpos_logs')->insert([
                'tanggal' => date('Y-m-d'),
                'company_name' => $request->input('company_name'),
                'company_address' => $request->input('company_address'),
                'company_email' => $request->input('company_email'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
                'company_pic' => $request->input('company_pic'),
                'jabatan' => $request->input('jabatan'),
                'no_hp' => $request->input('no_hp'),
                'jenis_pekerjaan' => $request->input('jenis_pekerjaan'),
                'deskripsi' => $request->input('deskripsi'),
                'lokasi' => $request->input('lokasi'),
                'bahaya' => $request->input('bahaya'),
                'lingkungan' => $request->input('lingkungan'),
                'prosedur' => $request->input('prosedur'),
                'safety' => $request->input('safety'),
                'peringatan' => $request->input('peringatan'),
                'ketentuan' => $request->input('ketentuan'),
                'pic_ympi' => $request->input('pic_ympi'),
                'departemen' => $request->input('departemen'),
                'work_permit' => $request->input('work_permit'),
                'type' => $type,
                'location' => $location,
                'question1' => $question1,
                'question2' => $question2,
                'question3' => $question3,
                'question4' => $question4,
                'vendor_accept' => $request->input('vendor_accept'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // $forms->save();

            // $isimail = "select * from wpos_logs where id = ".$forms->id;
            // $mail = db::select($isimail);

            // $mail = DB::table('wpos_logs')
            // ->where('id',$forms->id)
            // ->get();

            // Mail::to(['widura@music.yamaha.com'])->cc('prawoto@music.yamaha.com')->bcc(['rio.irvansyah@music.yamaha.com','mokhamad.khamdan.khabibi@music.yamaha.com'])->send(new SendEmail($mail, 'guest'));

            // Mail::to(['rio.irvansyah@music.yamaha.com'])
            // ->bcc(['mokhamad.khamdan.khabibi@music.yamaha.com'])
            // ->send(new SendEmail($mail, 'wpos'));

            $response = array(
                'status' => true,
                'datas' => 'Berhasil Input Data'
            );
            return Response::json($response);

        } catch (QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                $response = array(
                    'status' => false,
                    'datas' => 'Anda Sudah Mengisi Ini'
                );
                return Response::json($response);
            }
            else{
                $response = array(
                    'status' => false,
                    'datas' => $e->getMessage()
                );
                return Response::json($response);
            }
        }
    }

    public function indexCms(){
        return view('cms_vendor');
    }

    public function inputCms(Request $request)
    {
        try {
            $tujuan_upload = 'files/cms';
            
            $file_cms = $request->file('file_cms');

            if ($file_cms != NULL) {
                $nama = $file_cms->getClientOriginalName();
                $filename = pathinfo($nama, PATHINFO_FILENAME);
                $extension = pathinfo($nama, PATHINFO_EXTENSION);
                $filename = md5($filename.date('YmdHisa')).'.'.$extension;
                $file_cms->move($tujuan_upload,$filename);
            }
            else{
                $filename = NULL;
            }

            $forms = CmsVendor::create([
                'tanggal' => date('Y-m-d'),
                'name' => $request->input('name'),
                'company' => $request->input('company'),
                'question' => $request->input('question'),
                'answer' => $request->input('answer'),
                'file' => $filename
            ]);

            $forms->save();    

            $response = array(
                'status' => true,
                'datas' => 'Berhasil Input Data'
            );
            return Response::json($response);

        } catch (QueryException $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                $response = array(
                    'status' => false,
                    'datas' => 'Anda Sudah Mengisi Ini'
                );
                return Response::json($response);
            }
            else{
                $response = array(
                    'status' => false,
                    'datas' => $e->getMessage()
                );
                return Response::json($response);
            }
        }
    }


}
