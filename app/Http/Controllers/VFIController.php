<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use Response;
use Excel;
use App\Models\QaMaterial;
use App\Models\ErrorLog;
use App\Models\QaOutgoingVendor;
use App\Models\QaOutgoingVendorRecheck;
use App\Models\QaInspectionLevel;
use App\Models\QaOutgoingSerialNumber;
use App\Models\QaOutgoingVendorFinal;
use App\Models\CodeGenerator;
use App\Models\QaOutgoingPointCheck;

class VFIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if (isset($_SERVER['HTTP_USER_AGENT']))
        {
            $http_user_agent = $_SERVER['HTTP_USER_AGENT']; 
            if (preg_match('/Word|Excel|PowerPoint|ms-office/i', $http_user_agent)) 
            {
                // Prevent MS office products detecting the upcoming re-direct .. forces them to launch the browser to this link
                die();
            }
        }

        $this->critical_true = ['Berjamur',
            'Kotor Serangga',
            'Flashing',
            'Salah Spec',
            'Salah Label',
            'Material Kurang',
            'Material Lebih',
            'Material Tercampur',
            'Tidak Terplating',
            'Plating Kelupas',
            'Tanpa Shoulder Strap',
            'Hook Patah',
        ];

        $this->non_critical_true = ['Part Cuil',
            'Cloth Gundul',
            'Terlihat Kayu',
            'Terlihat Styrofoam',
            'Sobek',
            'Kelupas',
            'Celah',
            'Kaku',
            'Longgar',
            'Kerut',
            'Coretan',
            'Kotor Lem',
            'Karat',
            'Kotor Tinta / Kapur',
            'Scratch / Gores',
            'Bergelombang',
            'Cekung',
            'Cembung',
            'Miring',
            'Geser Sliding',
            'Buram',
            'Belang',
            'Painting Terkontamintasi',
            'Plating Beleber',
            'Plating Tipis',
            'Plating Kasar',
            'Sisa Benang',
            'Bahan Kain Kurang',
            'Part Goyang',
            'Jahitan Lepas',
        ];

        $this->critical_arisa = ['Bari (flashing)',
                    'Short-shoot',
                    'Salah kunci',
                    'Salah spec',
                    'Material kurang'];

        $this->non_critical_arisa = [
            'Kizu (scratch)',
            'Blackmark',
            'Kake (cuil)',
            'Flowmark',
            'Flowmark',
            'Silver',
            'Ketinggian kunci',
            'Katai (kaku)',
            'Yurui (longgar)',
            'Coretan',
            'Ana (berlubang)',
            'Sukima (celah)',
            'Nami (bergelombang)',
            'Heko (cekung)',
            'Deko (cembung)',
            'Overpack',
            'Ware (retak)',
            'Toke (meleleh)',
            'Usui (tipis)',
            'Atsui (tebal)',
            'Noise / suara benda asing',
            'Sinmark',
            'Buram',
            'Belang',
            'Terlalu terang',
            'Terlalu gelap',
            'Shiny (berkilau)',
            'Yogore (kotor)',
            'Kotor serangga',
            'Butsu (bintik jarum)',
            'Kumori (kusam)',
            'Bending',
            'Yabure (sobek)',
            'Hagare (kelupas)',
            'Zure (geser)',
            'Shiwa (kerut)',
            'Salah label',
            'Material lebih',
            'Material tercampur',
            'Twist (mulet)',
        ];

        $this->critical_kbi = ['Bari (flashing)',
                    'Short-shoot',
                    'Salah kunci',
                    'Salah spec',
                    'Material kurang'];

        $this->non_critical_kbi = ['Ibutsu',
            'Tankabutsu',
            'Dirty / Kotor',
            'Penyok',
            'Kontaminasi',
            'Thickness Tebal',
            'Thickness Tipis',
            'Material  Mentah',
            'Over Cutting',
            'Scratch / Kizu',
            'White spot / Hakka',
            'Shiwa / kerut',
            'Die line',
            'White line',
            'Henkei / kembung',
            'Dekok',
            'Short Mold',
            'Crack',
            'Pinhole',
            'Fitting NG',
            'Step',
            'Cutting Bergerigi',
            'Flashing',
            'Peel Off',
            'Case lock yurui / longgar',
            'Case lock katai / berat',
            'Nikudamari',
            'Noise',
            'Buble',
            'Weight NG',
            'Sukima / celah',
            'Child Part NG',
            'Insert Child Part NG',
            'Hole NG ',
            'Leak Test Bocor',
            'Drilling NG',
            'Doll',
            'Hekomi',
            'Terbakar/meleleh',
            'Cutting NG',
            'Henkei/Kembung',
            'Chiping',
            'PL Line NG',
            'Cutting tajam',
            'Vacuum NG',
        ];
    }
    public function index()
    {
        $vendor = Auth::user()->name;


            return view('vfi.index', [
                'vendor' => $vendor,
            ]);

    }

    public function indexVFITrue()
    {
        $title = 'Input Final Inspection';
        $title_jp = '';

        $ng_lists = DB::SELECT("select * from ng_lists where ng_lists.location = 'outgoing' and remark = 'true'");

        $materials = QaMaterial::where('vendor_shortname','TRUE')->get();

        return view('vfi.true.index_vfi', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'ng_lists' => $ng_lists,
            'vendor' => 'PT. TRUE INDONESIA',
            'inspector' => Auth::user()->name,
            'materials' => $materials,
        ))->with('page', 'Input Final Inspection TRUE')->with('head', 'Input Final Inspection TRUE');
    }

    public function fetchVFITrue(Request $request)
    {
        try {
            $target = QaOutgoingSerialNumber::where(DB::RAW('DATE_FORMAT(date,"%Y-%m")'),date('Y-m',strtotime($request->get('periode'))))->where('material_number',$request->get('material_number'))->first();

            if ($target) {
                $response = array(
                    'status' => true,
                    'target' => $target,
                );
                return Response::json($response);
            }
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);
        }
    }

    public function fetchKensaSerialNumber($vendor)
    {
        try {
            $code_generator = CodeGenerator::where('note', '=', $vendor)->first();
            $serial_number = $code_generator->prefix.sprintf("%'.0" . $code_generator->length . "d", $code_generator->index+1);
            $code_generator->index = $code_generator->index+1;
            $code_generator->save();

            $response = array(
                'status' => true,
                'serial_number' => $serial_number
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

    public function inputVFITrue(Request $request)
    {
        try {

            $material_number = $request->get('material_number');
            $material_description = $request->get('material_description');
            $qty_check = $request->get('qty_check');
            $total_ok = $request->get('total_ok');
            $total_ng = $request->get('total_ng');
            $ng_ratio = $request->get('ng_ratio');
            $inspector = $request->get('inspector');
            $ng_name = $request->get('ng_name');
            $ng_qty = $request->get('ng_qty');
            $jumlah_ng = $request->get('jumlah_ng');
            // $check_date = $request->get('check_date');
            $check_date = date('Y-m-d');
            $serial_number = $request->get('serial_number');
            $material = QaMaterial::where('material_number',$material_number)->first();
            $outgoings = [];
            $outgoing_id = [];
            $outgoings_critical = [];
            $outgoings_non_critical = [];
            if ($total_ng == 0) {
                $outgoing = new QaOutgoingVendor([
                    'check_date' => $check_date,
                    'material_number' => $material_number,
                    'material_description' => $material_description,
                    'serial_number' => $serial_number,
                    'vendor' => $material->vendor,
                    'vendor_shortname' => $material->vendor_shortname,
                    'hpl' => $material->hpl,
                    'inspector' => $inspector,
                    'qty_check' => $qty_check,
                    'total_ok' => $total_ok,
                    'total_ng' => $total_ng,
                    'ng_ratio' => $ng_ratio,
                    'ng_name' => '-',
                    'ng_qty' => '0',
                    'lot_status' => 'LOT OK',
                    'created_by' => Auth::user()->id
                ]);
                $outgoing->save();
            }else{
                for ($i=0; $i < count($ng_name); $i++) { 
                    $outgoing = new QaOutgoingVendor([
                        'check_date' => $check_date,
                        'material_number' => $material_number,
                        'material_description' => $material_description,
                        'vendor' => $material->vendor,
                        'vendor_shortname' => $material->vendor_shortname,
                        'hpl' => $material->hpl,
                        'inspector' => $inspector,
                        'serial_number' => $serial_number,
                        'qty_check' => $qty_check,
                        'total_ok' => $total_ok,
                        'total_ng' => $total_ng,
                        'ng_ratio' => $ng_ratio,
                        'ng_name' => $ng_name[$i],
                        'ng_qty' => $ng_qty[$i],
                        'created_by' => Auth::user()->id,
                    ]);

                    $outgoing->save();

                    array_push($outgoing_id, $outgoing->id);
                    if (in_array($ng_name[$i], $this->critical_true)) {
                        $mail_to = [];

                        array_push($mail_to, 'true.indonesia@yahoo.com');
                        array_push($mail_to, 'truejhbyun@naver.com');
                        array_push($mail_to, 'agustina.hayati@music.yamaha.com');
                        array_push($mail_to, 'ratri.sulistyorini@music.yamaha.com');
                        array_push($mail_to, 'abdissalam.saidi@music.yamaha.com');
                        array_push($mail_to, 'noviera.prasetyarini@music.yamaha.com');
                        array_push($mail_to, 'imbang.prasetyo@music.yamaha.com');
                        array_push($mail_to, 'ardianto@music.yamaha.com');

                        $cc = [];
                        $cc[0] = 'yayuk.wahyuni@music.yamaha.com';

                        $bcc = [];
                        $bcc[0] = 'mokhamad.khamdan.khabibi@music.yamaha.com';
                        $bcc[1] = 'rio.irvansyah@music.yamaha.com';

                        $outgoing_update = QaOutgoingVendor::where('id',$outgoing->id)->first();
                        $outgoing_update->lot_status = 'LOT OUT';
                        $outgoing_update->save();

                        $outgoing_criticals = QaOutgoingVendor::where('id',$outgoing->id)->first();
                        array_push($outgoings_critical, $outgoing_criticals);

                        Mail::to($mail_to)
                        ->cc($cc,'CC')
                        ->bcc($bcc,'BCC')
                        ->send(new SendEmail($outgoing_criticals, 'critical_true'));
                    }

                    if (in_array($ng_name[$i], $this->non_critical_true)) {
                        array_push($outgoings, $outgoing);
                    }
                }

                $total_ng_non = 0;
                for ($i=0; $i < count($outgoings); $i++) { 
                    $total_ng_non = $total_ng_non + $outgoings[$i]->ng_qty;
                }

                if ($total_ng_non != 0) {
                    $persen = ($total_ng_non/$qty_check)*100;
                    if ($persen > 5) {
                        $mail_to = [];

                        array_push($mail_to, 'true.indonesia@yahoo.com');
                        array_push($mail_to, 'truejhbyun@naver.com');
                        array_push($mail_to, 'agustina.hayati@music.yamaha.com');
                        array_push($mail_to, 'ratri.sulistyorini@music.yamaha.com');
                        array_push($mail_to, 'abdissalam.saidi@music.yamaha.com');
                        array_push($mail_to, 'noviera.prasetyarini@music.yamaha.com');
                        array_push($mail_to, 'imbang.prasetyo@music.yamaha.com');
                        array_push($mail_to, 'ardianto@music.yamaha.com');

                        $cc = [];
                        $cc[0] = 'yayuk.wahyuni@music.yamaha.com';
                        // $cc[1] = 'imron.faizal@music.yamaha.com';

                        $bcc = [];
                        $bcc[0] = 'mokhamad.khamdan.khabibi@music.yamaha.com';
                        $bcc[1] = 'rio.irvansyah@music.yamaha.com';

                        for ($i=0; $i < count($outgoing_id); $i++) { 
                            $outgoing_update = QaOutgoingVendor::where('id',$outgoing_id[$i])->first();
                            $outgoing_update->lot_status = 'LOT OUT';
                            $outgoing_update->save();

                            $outgoing_non_critical = QaOutgoingVendor::where('id',$outgoing_id[$i])->first();
                            array_push($outgoings_non_critical, $outgoing_non_critical);
                        }

                        $data = array(
                            'outgoing_non' => $outgoings_non_critical,
                            'outgoing_critical' => $outgoings_critical, );

                        Mail::to($mail_to)
                        ->cc($cc,'CC')
                        ->bcc($bcc,'BCC')
                        ->send(new SendEmail($data, 'over_limit_ratio_true'));
                    }
                }
            }
            
            $response = array(
                'status' => true,
                'message' => 'Success Input Data',
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

    public function indexVFINGRate($vendor)
    {
        if ($vendor == 'true') {
            $title = 'Production NG Rate PT. TRUE';
            $page = 'Production NG Rate TRUE';
            $title_jp = '';
            $vendor_name = 'PT. TRUE';
            $view = 'vfi.true.ng_rate';
            $materials = QaMaterial::where('vendor_shortname','TRUE')->get();
        }

        if ($vendor == 'arisa') {
            $title = 'Production NG Rate PT. ARISA';
            $page = 'Production NG Rate ARISA';
            $title_jp = '';
            $vendor_name = 'PT. ARISAMANDIRI PRATAMA';
            $view = 'vfi.arisa.ng_rate';
            $materials = QaMaterial::where('vendor_shortname','ARISA')->get();
        }

        if ($vendor == 'kbi') {
            $title = 'Production NG Rate PT. KBI';
            $page = 'Production NG Rate KBI';
            $title_jp = '';
            $vendor_name = 'PT. KBI';
            $view = 'vfi.kbi.ng_rate';
            $materials = QaMaterial::where('vendor_shortname','KBI')->get();
        }

        if ($vendor == 'crestec') {
            $title = 'Production NG Rate PT. CRESTEC INDONESIA';
            $page = 'Production NG Rate CRESTEC';
            $title_jp = '';
            $vendor_name = 'CRESTEC INDONESIA PT';
            $view = 'vfi.crestec.ng_rate';
            $materials = QaMaterial::where('vendor_shortname','CRESTEC')->get();
        }

        if ($vendor == 'lti') {
            $title = 'Production NG Rate PT. LIMA TEKNO INDONESIA';
            $page = 'Production NG Rate LTI';
            $title_jp = '';
            $vendor_name = 'PT. LIMA TEKNO  INDONESIA';
            $view = 'vfi.lti.ng_rate';
            $materials = QaMaterial::where('vendor_shortname','LTI')->get();
        }

        if ($vendor == 'cpp') {
            $title = 'Production NG Rate PT. CONTINENTAL PANJIPRATAMA';
            $page = 'Production NG Rate CPP';
            $title_jp = '';
            $vendor_name = 'PT. CONTINENTAL PANJIPRATAMA';
            $view = 'vfi.cpp.ng_rate';
            $materials = QaMaterial::where('vendor_shortname','CONTINENTAL')->get();
        }

        return view($view, array(
            'title' => $title,
            'title_jp' => $title_jp,
            'vendor' => $vendor,
            'materials' => $materials,
        ))->with('page', $page)->with('head', $page);
    }

    public function fetchVFINGRate(Request $request,$vendor)
    {
        try {
            if ($vendor == 'arisa') {
                $vendor_shortname = 'ARISA';
            }
            if ($vendor == 'true') {
                $vendor_shortname = 'TRUE';
            }
            if ($vendor == 'kbi') {
                $vendor_shortname = 'KYORAKU';
            }
            if ($vendor == 'crestec') {
                $vendor_shortname = 'CRESTEC';
            }
            if ($vendor == 'lti') {
                $vendor_shortname = 'LTI';
            }
            if ($vendor == 'cpp') {
                $vendor_shortname = 'CONTINENTAL';
            }

            $date_from = $request->get('date_from');
            $date_to = $request->get('date_to');
            if ($date_from == "") {
                 if ($date_to == "") {
                      $first = "DATE_FORMAT( NOW(), '%Y-%m-01' ) ";
                      $last = "LAST_DAY(NOW())";
                      $firstDateTitle = date('01 M Y');
                      $lastDateTitle = date('d M Y');
                 }else{
                      $first = "DATE_FORMAT( NOW(), '%Y-%m-01' ) ";
                      $last = "'".date('Y-m-d',strtotime($date_to))."'";
                      $firstDateTitle = date('01 M Y');
                      $lastDateTitle = date('d M Y',strtotime($date_to));
                 }
            }else{
                 if ($date_to == "") {
                      $first = "'".date('Y-m-d',strtotime($date_from))."'";
                      $last = "LAST_DAY(NOW())";
                      $firstDateTitle = date('d M Y',strtotime($date_from));
                      $lastDateTitle = date('d M Y');
                 }else{
                      $first = "'".date('Y-m-d',strtotime($date_from))."'";
                      $last = "'".date('Y-m-d',strtotime($date_to))."'";
                      $firstDateTitle = date('d M Y',strtotime($date_from));
                      $lastDateTitle = date('d M Y',strtotime($date_to));
                 }
            }

            $material = '';
            if($request->get('material') != null){
              $materials =  explode(",", $request->get('material'));
              for ($i=0; $i < count($materials); $i++) {
                $material = $material."'".$materials[$i]."'";
                if($i != (count($materials)-1)){
                  $material = $material.',';
                }
              }
              $materialin = " and `material_number` in (".$material.") ";
            }
            else{
              $materialin = "";
            }

            $outgoing = DB::SELECT("SELECT
                c.check_date,
                SUM( c.qty_check ) AS qty_check,
                SUM( c.qty_ng ) AS qty_ng,
                ROUND(( SUM( c.qty_ng ) / SUM( c.qty_check ) )* 100, 2 ) AS ng_ratio 
            FROM
                (
                SELECT DISTINCT
                    ( serial_number ),
                    DATE( created_at ) AS check_date,
                    ( SELECT a.qty_check FROM `qa_outgoing_vendors` AS a WHERE a.serial_number = qa_outgoing_vendors.serial_number LIMIT 1 ) AS qty_check,
                    ( SELECT sum( b.ng_qty ) FROM `qa_outgoing_vendors` AS b WHERE b.serial_number = qa_outgoing_vendors.serial_number ) AS qty_ng 
                FROM
                    `qa_outgoing_vendors` 
                WHERE
                    vendor_shortname = '".$vendor_shortname."' 
                    ".$materialin."
                    AND DATE( created_at ) >= ".$first."
                    AND DATE( created_at ) <= ".$last." 
                ) c 
            GROUP BY
                c.check_date");
            $response = array(
                'status' => true,
                'outgoing' => $outgoing,
                'firstDateTitle' => $firstDateTitle,
                'lastDateTitle' => $lastDateTitle,
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

    public function fetchVFINGRateDetail(Request $request,$vendor)
    {
        try {
            if ($vendor == 'arisa') {
                $vendor_shortname = 'ARISA';
            }
            if ($vendor == 'true') {
                $vendor_shortname = 'TRUE';
            }
            if ($vendor == 'kbi') {
                $vendor_shortname = 'KYORAKU';
            }
            if ($vendor == 'crestec') {
                $vendor_shortname = 'CRESTEC';
            }
            if ($vendor == 'lti') {
                $vendor_shortname = 'LTI';
            }
            if ($vendor == 'cpp') {
                $vendor_shortname = 'CONTINENTAL';
            }

            $material = '';
            if($request->get('material') != null){
              $materials =  explode(",", $request->get('material'));
              for ($i=0; $i < count($materials); $i++) {
                $material = $material."'".$materials[$i]."'";
                if($i != (count($materials)-1)){
                  $material = $material.',';
                }
              }
              $materialin = " and `material_number` in (".$material.") ";
            }
            else{
              $materialin = "";
            }

            $outgoing = DB::SELECT("SELECT
                  *,DATE(created_at) as created
                FROM
                  `qa_outgoing_vendors` 
                WHERE
                  vendor_shortname = '".$vendor_shortname."' 
                  AND DATE( created_at ) = '".$request->get('categories')."'
                  ".$materialin."");

            $response = array(
                'status' => true,
                'outgoing' => $outgoing,
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

    public function indexVFIPareto($vendor)
    {
        if ($vendor == 'true') {
            $title = 'Production Pareto PT. TRUE';
            $page = 'Production Pareto TRUE';
            $title_jp = '';
            $vendor_name = 'PT. TRUE';
            $view = 'vfi.true.pareto';
            $materials = QaMaterial::where('vendor_shortname','TRUE')->get();
        }

        if ($vendor == 'arisa') {
            $title = 'Production Pareto PT. ARISA';
            $page = 'Production Pareto ARISA';
            $title_jp = '';
            $vendor_name = 'PT. ARISAMANDIRI PRATAMA';
            $view = 'vfi.arisa.pareto';
            $materials = QaMaterial::where('vendor_shortname','ARISA')->get();
        }

        if ($vendor == 'kbi') {
            $title = 'Production Pareto PT. KBI';
            $page = 'Production Pareto KBI';
            $title_jp = '';
            $vendor_name = 'PT. KBI';
            $view = 'vfi.kbi.pareto';
            $materials = QaMaterial::where('vendor_shortname','KYORAKU')->get();
        }

        if ($vendor == 'crestec') {
            $title = 'Production Pareto PT. CRESTEC INDONESIA';
            $page = 'Production Pareto CRESTEC';
            $title_jp = '';
            $vendor_name = 'PT. CRESTEC INDONESIA';
            $view = 'vfi.crestec.pareto';
            $materials = QaMaterial::where('vendor_shortname','CRESTEC')->get();
        }

        if ($vendor == 'lti') {
            $title = 'Production Pareto PT. LIMA TEKNO INDONESIA';
            $page = 'Production Pareto LTI';
            $title_jp = '';
            $vendor_name = 'PT. LIMA TEKNO INDONESIA';
            $view = 'vfi.lti.pareto';
            $materials = QaMaterial::where('vendor_shortname','LTI')->get();
        }

        if ($vendor == 'cpp') {
            $title = 'Production Pareto PT. CONTINENTAL PANJIPRATAMA';
            $page = 'Production Pareto CPP';
            $title_jp = '';
            $vendor_name = 'PT. CONTINENTAL PANJIPRATAMA';
            $view = 'vfi.cpp.pareto';
            $materials = QaMaterial::where('vendor_shortname','CONTINENTAL')->get();
        }

        return view($view, array(
            'title' => $title,
            'title_jp' => $title_jp,
            'vendor' => $vendor,
            'materials' => $materials,
        ))->with('page', $page)->with('head', $page);
    }

    public function fetchVFIPareto(Request $request,$vendor)
    {
        try {
            if ($vendor == 'arisa') {
                $vendor_shortname = 'ARISA';
            }
            if ($vendor == 'true') {
                $vendor_shortname = 'TRUE';
            }
            if ($vendor == 'kbi') {
                $vendor_shortname = 'KYORAKU';
            }
            if ($vendor == 'crestec') {
                $vendor_shortname = 'CRESTEC';
            }
            if ($vendor == 'lti') {
                $vendor_shortname = 'LTI';
            }
            if ($vendor == 'cpp') {
                $vendor_shortname = 'CONTINENTAL';
            }

            $first_month_ng = DB::SELECT("SELECT
              DATE_FORMAT( week_date, '%Y-%m' ) AS first_month 
            FROM
              weekly_calendars 
            WHERE
              fiscal_year = (
              SELECT
                fiscal_year 
              FROM
                weekly_calendars 
              WHERE
                week_date = DATE(
                NOW())) 
            ORDER BY
              week_date 
              LIMIT 1");
            $month_from = $request->get('month_from');
            $month_to = $request->get('month_to');
            if ($month_from == "") {
                 if ($month_to == "") {
                      $first = "DATE_FORMAT( NOW(), '%Y-%m' )";
                      $firstDate = "DATE_FORMAT( NOW(), '%Y-%m-01' )";
                      $last = "DATE_FORMAT( NOW(), '%Y-%m' )";
                      $lastDate = "DATE_FORMAT( NOW(), '%Y-%m-%d' )";
                      $firstMonthTitle = date('M Y');
                      $lastMonthTitle = date('M Y');
                 }else{
                      $first = "DATE_FORMAT( NOW(), '%Y-%m' )";
                      $firstDate = "DATE_FORMAT( NOW(), '%Y-%m-01' )";
                      $last = "'".$month_to."'";
                      $lastDate = "'".$month_to."-".date('d')."'";
                      $firstMonthTitle = date('M Y');
                      $lastMonthTitle = date('M Y',strtotime($month_to));
                 }
            }else{
                 if ($month_to == "") {
                      $first = "'".$month_from."'";
                      $firstDate = "'".$month_from."-01'";
                      $last = "DATE_FORMAT( NOW(), '%Y-%m' )";
                      $lastDate = "DATE_FORMAT( NOW(), '%Y-%m-%d' )";
                      $firstMonthTitle = date('M Y',strtotime($month_from));
                      $lastMonthTitle = date('M Y');
                 }else{
                      $first = "'".$month_from."'";
                      $firstDate = "'".$month_from."'-01";
                      $last = "'".$month_to."'";
                      $lastDate = "'".$month_to."-".date('d')."'";
                      $firstMonthTitle = date('M Y',strtotime($month_from));
                      $lastMonthTitle = date('M Y',strtotime($month_to));
                 }
            }

            $material = '';
            if($request->get('material') != null){
              $materials =  explode(",", $request->get('material'));
              for ($i=0; $i < count($materials); $i++) {
                $material = $material."'".$materials[$i]."'";
                if($i != (count($materials)-1)){
                  $material = $material.',';
                }
              }
              $materialin = " and `material_number` in (".$material.") ";
            }
            else{
              $materialin = "";
            }

            $material_defect = DB::SELECT("SELECT
                ng_name,
                SUM( ng_qty ) AS count,
                SUM( total_ok ) AS count_ok,
                SUM( qa_outgoing_vendors.qty_check ) AS count_check 
            FROM
                qa_outgoing_vendors 
            WHERE
                DATE_FORMAT( qa_outgoing_vendors.created_at, '%Y-%m' ) >= ".$first." 
                AND DATE_FORMAT( qa_outgoing_vendors.created_at, '%Y-%m' ) <= ".$last."
                and vendor_shortname = '".$vendor_shortname."'
                and ng_name != '-'
                ".$materialin."
            GROUP BY
                ng_name 
            ORDER BY
                count DESC,
                count_ok DESC,
                count_check DESC");

            $material_status = DB::SELECT("SELECT
                    qa_outgoing_vendors.material_number,
                    material_description,
                    sum( ng_qty ) AS qty,
                    a.qty_check,
                    ROUND(( SUM( ng_qty ) / SUM( a.qty_check ) )* 100, 2 ) AS ng_ratio 
                FROM
                    qa_outgoing_vendors
                    JOIN (
                    SELECT
                        c.material_number,
                        SUM( c.qty_check ) AS qty_check 
                    FROM
                        (
                        SELECT DISTINCT
                            ( serial_number ),
                            material_number,
                            DATE( created_at ) AS check_date,
                            ( SELECT a.qty_check FROM `qa_outgoing_vendors` AS a WHERE a.serial_number = qa_outgoing_vendors.serial_number LIMIT 1 ) AS qty_check 
                        FROM
                            `qa_outgoing_vendors` 
                        WHERE
                            vendor_shortname = '".$vendor_shortname."' 
                            AND DATE( created_at ) >= ".$firstDate."
                            AND DATE( created_at ) <= ".$lastDate."
                            AND ng_qty != 0 
                        ) c 
                    GROUP BY
                        c.material_number 
                    ) a ON a.material_number = qa_outgoing_vendors.material_number 
                WHERE
                    vendor_shortname = '".$vendor_shortname."' 
                    AND DATE_FORMAT( created_at, '%Y-%m' ) >= ".$first."
                    AND DATE_FORMAT( created_at, '%Y-%m' ) <= ".$last." 
                GROUP BY
                    material_number,
                    material_description,
                    a.qty_check
                ORDER BY
                    ng_ratio DESC 
                    LIMIT 5");
            $response = array(
                'status' => true,
                'material_defect' => $material_defect,
                'material_status' => $material_status,
                'firstMonthTitle' => $firstMonthTitle,
                'lastMonthTitle' => $lastMonthTitle,
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

    public function fetchVFIParetoDetail(Request $request,$vendor)
    {
        try {
            if ($vendor == 'arisa') {
                $vendor_shortname = 'ARISA';
            }
            if ($vendor == 'true') {
                $vendor_shortname = 'TRUE';
            }
            if ($vendor == 'kbi') {
                $vendor_shortname = 'KYORAKU';
            }
            if ($vendor == 'crestec') {
                $vendor_shortname = 'CRESTEC';
            }
            if ($vendor == 'lti') {
                $vendor_shortname = 'LTI';
            }
            if ($vendor == 'cpp') {
                $vendor_shortname = 'CONTINENTAL';
            }

            $month_from = $request->get('month_from');
            $month_to = $request->get('month_to');
            if ($month_from == "") {
                 if ($month_to == "") {
                      $first = "DATE_FORMAT( NOW(), '%Y-%m' )";
                      $last = "DATE_FORMAT( NOW(), '%Y-%m' )";
                      $firstMonthTitle = date('M Y');
                      $lastMonthTitle = date('M Y');
                 }else{
                      $first = "DATE_FORMAT( NOW(), '%Y-%m' )";
                      $last = "'".$month_to."'";
                      $firstMonthTitle = date('M Y');
                      $lastMonthTitle = date('M Y',strtotime($month_to));
                 }
            }else{
                 if ($month_to == "") {
                      $first = "'".$month_from."'";
                      $last = "DATE_FORMAT( NOW(), '%Y-%m' )";
                      $firstMonthTitle = date('M Y',strtotime($month_from));
                      $lastMonthTitle = date('M Y');
                 }else{
                      $first = "'".$month_from."'";
                      $last = "'".$month_to."'";
                      $firstMonthTitle = date('M Y',strtotime($month_from));
                      $lastMonthTitle = date('M Y',strtotime($month_to));
                 }
            }

            $material = '';
            if($request->get('material') != null){
              $materials =  explode(",", $request->get('material'));
              for ($i=0; $i < count($materials); $i++) {
                $material = $material."'".$materials[$i]."'";
                if($i != (count($materials)-1)){
                  $material = $material.',';
                }
              }
              $materialin = " and `material_number` in (".$material.") ";
            }
            else{
              $materialin = "";
            }

            $details = DB::SELECT("SELECT
                    *,
                    DATE( created_at ) AS created 
                FROM
                    `qa_outgoing_vendors` 
                WHERE
                    vendor_shortname = '".$vendor_shortname."' 
                    AND DATE_FORMAT( created_at, '%Y-%m' ) >= ".$first." 
                    AND DATE_FORMAT( created_at, '%Y-%m' ) <= ".$last."
                    AND ng_name = '".$request->get('categories')."'
                  ".$materialin."");

            $response = array(
                'status' => true,
                'details' => $details,
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

    public function indexVFIReportTrue()
    {
        $title = 'Report Production Check PT. TRUE INDONESIA';
        $page = 'Report Production Check PT. TRUE INDONESIA';
        $title_jp = '';

        $materials = QaMaterial::where('vendor_shortname','TRUE')->get();

        return view('vfi.true.report', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'materials' => $materials,
            'vendor' => 'PT. TRUE',
        ))->with('page', $page)->with('head', $page);
    }

    public function fetchVFIReportTrue(Request $request)
    {
        try {

            $date_from = $request->get('date_from');
            $date_to = $request->get('date_to');
            if ($date_from == "") {
                 if ($date_to == "") {
                      $first = date('Y-m-d',strtotime('-2 months'));
                      $last = date('Y-m-d');
                 }else{
                      $first = date('Y-m-d',strtotime('-2 months'));
                      $last = $date_to;
                 }
            }else{
                 if ($date_to == "") {
                      $first = $date_from;
                      $last = date('Y-m-d');
                 }else{
                      $first = $date_from;
                      $last = $date_to;
                 }
            }

            $outgoing = QaOutgoingVendor::select('qa_outgoing_vendors.*','qa_outgoing_vendors.created_at as created')->where('qa_outgoing_vendors.vendor_shortname','TRUE')
            ->where(DB::RAW('DATE(qa_outgoing_vendors.created_at)'),'>=',$first)
            ->where(DB::RAW('DATE(qa_outgoing_vendors.created_at)'),'<=',$last);

            if($request->get('material') != null){
              $materials =  explode(",", $request->get('material'));
              $outgoing = $outgoing->whereIn('qa_outgoing_vendors.material_number',$materials);
            }

            $outgoing = $outgoing->orderby('qa_outgoing_vendors.created_at','desc')->get();

            $response = array(
                'status' => true,
                'outgoing' => $outgoing,
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
