<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;

class SettingController extends Controller
{
    function index() {
        $title = 'MIS Setting';
        $title_jp = "MIS設定";
        $whatsapp = DB::table('all_controls')->where('note', 'whatsapp')->get();

        return view('setting.index', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'whatsapp' => $whatsapp,
        ))->with('page', 'MIS Setting')->with('head', 'MIS Setting');
    }

    public function changeWhatsapp(Request $request)
    {
        try {
            $id = $request->get('id');
            $remark = $request->get('remark');
            $otps = DB::table('all_controls')->where('id', $id)->update([
                'remark' => $remark,
                'last_updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $response = array(
                'status' => true,
            );
            return Response::json($response);
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);
        }
    }
}
