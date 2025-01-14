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


            $forms = DB::table('wpos_logs')->insertGetId([
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
                'status_approval' => 'Waiting',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $data_mail = DB::table('wpos_logs')
            ->where('id',$forms)
            ->first();

            $dept = explode(',', $request->input('departemen'));

            for ($i=0; $i < count($dept); $i++) { 
                $manager = db::table('approvers')->where('remark', '=', 'Manager')
                    ->where('department', '=', $dept[$i])
                    ->first();

                db::table('wpos_approvals')->insert([
                    'wpos_id' => $forms,
                    'approver_id' => $manager->approver_id,
                    'approver_name' => $manager->approver_name,
                    'approver_email' => $manager->approver_email,
                    'department' => $manager->department,
                    'status' => 'Waiting',
                    'position' => 'Manager',
                    'remark' => 'Approved By',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                
                $data = [
                    'dept' => $manager->department,
                    'wpos_data' => $data_mail
                ];


                Mail::to($manager->approver_email)
                ->bcc(['ympi-mis-ML@music.yamaha.com'])
                ->send(new SendEmail($data, 'wpos'));
            }

            db::table('wpos_approvals')->insert([
                'wpos_id' => $forms,
                'approver_id' => '',
                'approver_name' => '',
                'approver_email' => '',
                'department' => 'Standardization Department',
                'status' => 'Waiting',
                'position' => 'STD',
                'remark' => 'Checked By',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Mail::to(['widura@music.yamaha.com'])->cc('prawoto@music.yamaha.com')->bcc(['rio.irvansyah@music.yamaha.com','mokhamad.khamdan.khabibi@music.yamaha.com'])->send(new SendEmail($mail, 'guest'));

            // Mail::to(['rio.irvansyah@music.yamaha.com'])
            // ->cc(['mokhamad.khamdan.khabibi@music.yamaha.com'])
            // ->send(new SendEmail($data_mail, 'wpos'));

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


    public function approveWpos(Request $request)
    {
        try {

            $wpos = db::table('wpos_logs')
                ->where('id', '=', $request->get('wpos_id'))
                ->first();

            $approvers = db::table('wpos_approvals')
                ->where('wpos_id', '=', $request->get('wpos_id'))
                ->get();

            if ($request->get('status') == null) {
                
                $data = [
                    'dept' => $code,
                    'wpos_data' => $wpos
                ];

                // return view('about_mis.wpos.mail_approval_new', array(
                //     'data' => $data,
                // ));

            } else {

                $approvers = db::table('wpos_approvals')
                    ->where('wpos_id', '=', $request->get('wpos_id'))
                    ->get();

                $approver = db::table('wpos_approvals')
                    ->where('wpos_id', '=', $request->get('wpos_id'))
                    ->where('department', '=', $request->get('code'))
                    ->first();

                for ($i = 0; $i < count($approvers); $i++) {
                    if ($approvers[$i]->approver_id == $approver->approver_id) {
                        if ($i > 0) {
                            if ($approvers[$i - 1]->status != 'Approved') {
                                return view('notification', array(
                                    'title' => 'WPOS Approval',
                                    'title_jp' => '',
                                    'wpos' => $wpos,
                                    'status' => false,
                                    'message' => 'Previous approver has not approved or already rejected',
                                ));
                            }
                        }
                        break;
                    }
                }

                if (strtoupper(strtoupper(Auth::user()->username)) != $approver->approver_id) {
                    return view('notification', array(
                        'title' => 'WPOS Approval',
                        'title_jp' => '',
                        'wpos' => $wpos,
                        'status' => false,
                        'message' => 'You dont have authorization to approve WPOS',
                    ));
                }

                if ($approver->status != 'Waiting') {
                    return view('notification', array(
                        'title' => 'WPOS Approval',
                        'title_jp' => '',
                        'wpos' => $wpos,
                        'status' => false,
                        'message' => 'WPOS already approved/rejected',
                    ));
                }

                db::table('wpos_approvals')
                    ->where('wpos_id', '=', $request->get('wpos_id'))
                    ->where('approver_id', '=', $approver->approver_id)
                    ->update([
                        'status' => $request->get('status'),
                        'approved_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                if ($request->get('status') == 'Approved') {

                    $wpos = db::table('wpos_logs')
                        ->where('id', '=', $request->get('wpos_id'))
                        ->first();

                    $approvers = db::table('wpos_approvals')
                        ->where('wpos_id', '=', $request->get('wpos_id'))
                        ->get();

                    $next_approver = db::table('wpos_approvals')
                        ->where('wpos_id', '=', $request->get('wpos_id'))
                        ->whereNull('approved_at')
                        ->orderBy('id', 'ASC')
                        ->first();

                    if ($next_approver) {

                        $data = [
                            'code' => $next_approver->position,
                            'wpos' => $wpos,
                            'approver' => $approvers
                        ];

                        Mail::to($next_approver->approver_email)
                            ->bcc(['ympi-mis-ML@music.yamaha.com'])
                            ->send(new SendEmail($data, 'wpos'));

                    } else {
                        db::table('wpos_logs')
                            ->where('wpos_id', '=', $request->get('wpos_id'))
                            ->update([
                                'status_approval' => 'Fully Approved',
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                    }
                }

                if ($request->get('status') == 'Rejected') {

                    db::table('wpos_logs')
                        ->where('id', '=', $request->get('wpos_id'))
                        ->update([
                            'status_approval' => 'Rejected',
                            'reason_reject' => $request->get('reject_reason'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    $ticket = db::table('wpos_logs')
                        ->where('wpos_id', '=', $request->get('wpos_id'))
                        ->first();

                    $cd = array();
                    if (count($costdowns) > 0) {
                        foreach ($costdowns as $costdown) {
                            array_push($cd, [
                                'category' => $costdown->category,
                                'description' => $costdown->description,
                                'quantity' => $costdown->quantity,
                                'uom' => $costdown->uom,
                            ]);
                        }
                    }

                    $approvers = db::table('wpos_approvals')
                        ->where('ticket_id', '=', $request->get('ticket_id'))
                        ->get();

                    $user = db::table('users')->where('username', '=', $ticket->created_by)
                        ->first();

                    $data = [
                        'code' => 'rejected',
                        'ticket' => $ticket,
                        'costdown' => $cd,
                        'approver' => $approvers,
                        'filename' => $ticket->case_attachment,
                    ];

                    Mail::to($user->email)
                        ->cc('ympi-mis-ML@music.yamaha.com')
                        ->send(new SendEmail($data, 'mis_ticket_approval_new'));
                }

                return view('about_mis.ticket.notification', array(
                    'title' => 'MIS Ticket Approval.',
                    'title_jp' => '',
                    'ticket' => $ticket,
                    'status' => true,
                    'message' => 'Ticket successfully confirmed',
                ));
            }
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage() . ' ' . $e->getLine(),
            );
            return Response::json($response);
        }
    }

    public function rejectWpos(Request $request)
    {

        $ticket = db::table('wpos_logs')
            ->where('wpos_id', '=', $request->get('wpos_id'))
            ->first();

        $data = [
            'code' => $request->get('code'),
            'ticket' => $ticket,
            'costdown' => $cd,
            'approver' => $approver,
        ];

        return view('about_mis.ticket.mail_approval_reject_new', array(
            'data' => $data,
        ));
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
