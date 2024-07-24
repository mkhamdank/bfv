<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Response;
use Yajra\DataTables\Facades\DataTables;
// use DataTables;

class RecruitmentHrController extends Controller
{
    public function __construct()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $http_user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/Word|Excel|PowerPoint|ms-office/i', $http_user_agent)) {
                // Prevent MS office products detecting the upcoming re-direct .. forces them to launch the browser to this link
                die();
            }
        }
        $this->middleware('auth');
        $this->column = ['aa','ab','ba','bb','ca','cb','da','db','ea','eb','fa','fb','ga','gb','ha','hb','ia','ib','ja','jb','ka','kb','la','lb','ma','mb','na','nb','oa','ob','pa','pb','qa','qb','ra','rb','sa','sb','ta','tb','ua','ub','va','vb','wa','wb','xa','xb','ya','yb'];
        $this->row = [35,34,33,32,31,30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1];
    }

    public function changeRecruitmentSetting(Request $request)
    {
        ini_set('max_execution_time', -1);
        DB::beginTransaction();
        try{
            $status = $request->status;
            $type = $request->type;
            $updateSetting = DB::table('recruitment_settings')->where('type', $type)->update(['status'=>$status]);
            $setting = DB::table('recruitment_settings')->where('type', $type)->first();

            DB::commit();
            $response = ['status' => true, 'message' => 'Berhasil', 'data' => $setting];
            return Response::json($response);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['status' => false, 'message' => $e->getMessage()];
            return Response::json($response);
        }
    }

    public function checkTest(Request $request)
    {
        $participantId = $request->participant_id;
        $date = $request->test_date ?? date('Y-m-d');
        $checkTest = DB::table('recruitment_kraepelin_answers')
            ->where('date', $date)
            ->where('participant_id', $participantId)
            ->whereIn('column_number', [50])
            ->first();
        $testDone = ($checkTest) ? true : false;
        return $testDone;
    }

    private function resultCategory(Request $request)
    {
        $parameter = $request->parameter;
        $education = $request->education;
        $value = $request->value;

        $education = (in_array($education, ['S2','S3'])) ? 'D4/S1' : $education;
        $getResult = DB::table('recruitment_kraepelin_answer_categories')
            ->where('parameter', $parameter)
            ->where('education', $education)
            ->where(function ($q) use ($value) {
                $q->where('min', '<=', $value);
                $q->where('max', '>=', $value);
            })
            ->first();
        return $getResult;
    }

    public function monitoring(Request $request)
    {
        $title = "Rekrutmen PT. YMPI";
        $title_jp = "Rekrutmen PT. YMPI";

        $statusOpeningTest = DB::table('recruitment_settings')->where('type', 'opening_kraepelin_test')->first();

        if($request->ajax()){
            $date = $request->date;
            $ex = explode(' - ', $date);
            $start = @$ex[0];
            $end = @$ex[1];

            $getAnswer = DB::table('recruitment_participant_tests as rpt')
                ->leftJoin('recruitment_participants as rp', 'rpt.participant_id', '=', 'rp.id')
                ->select('rpt.participant_id', 'rpt.test_type', 'rpt.test_date', 'rp.card_id', 'rp.name', 'rp.education', 'rp.birth_date')
                ->whereBetween('rpt.test_date', [$start, $end])
                ->orderByDesc('rpt.test_date')
                ->get();

            if($getAnswer->count() > 0){
                foreach ($getAnswer as $k => $val) {
                    $getAnswer[$k]->age = Carbon::parse($val->birth_date)->age;

                    $request->test_date = $val->test_date;
                    $request->participant_id = $val->participant_id;
                    $testDone = $this->checkTest($request);
                    $getAnswer[$k]->test_done = $testDone;
                }
            }
            return DataTables::of($getAnswer)
                ->addIndexColumn()
                ->addColumn('action', function ($item){})
                ->make(true);
        }
        $compact = ['title','title_jp', 'statusOpeningTest'];
        return view('recruitment.monitoring.monitoring', compact($compact));
    }

    public function kraepelinResult(Request $request)
    {
        ini_set('max_execution_time', -1);
        DB::beginTransaction();
        try{
            $participantId = $request->participant_id;
            $testDate = $request->test_date;
            $testType = $request->test_type;
            $type = $request->type;

            $dilewati = 0;
            $salah = [];
            $benar = [];
            $benarByColumn = [];
            $countByColumn = [];
            $jumlahByColumn = [];
            $resultAnswer = [];
            $chartLabel = [];
            $statusKurang = [];
            $statusKurangSekali = [];
            $statusKurangSekaliKetelitian = [];

            $participant = DB::table('recruitment_participants')->where('id', $participantId)->first();
            $participant->age = Carbon::parse($participant->birth_date)->age;
            $participant->test_date = $testDate;

            $getQuestion = DB::table('recruitment_kraepelin_test')->orderBy('coordinate')->get();
            $listQuestions = [];
            $columnQuestions = [];
            foreach ($getQuestion as $key => $val) {
                $listQuestions[$val->coordinate] = $val->coordinate_value;
                $columnQuestions[$val->coordinate] = $val->column_number;
            }

            $question = DB::table('recruitment_kraepelin_test')->get();
            $answerByCoordinate = $question->pluck('answer', 'coordinate')->all();
            $answer = DB::table('recruitment_kraepelin_answers')
                ->where('date', $testDate)
                ->where('participant_id', $participantId)
                ->orderBy('column_number')
                ->get();

            $nilai_y = [];
            if($answer->count() > 0){
                foreach ($answer as $k => $val) {
                    if($val->answer == $answerByCoordinate[$val->coordinate]){
                        $benar[] = $val->coordinate;
                        $benarByColumn[$val->column_number][] = $val->coordinate;
                        $resultAnswer[$val->coordinate] = ['value'=>$val->answer, 'status'=>'benar'];
                    } else {
                        $salah[] = $val->coordinate;
                        $resultAnswer[$val->coordinate] = ['value'=>$val->answer, 'status'=>'salah'];
                    }
                    $countByColumn[$val->column_number][] = 1;
                }
            }

            $jumlahBenar = '';
            $urutanBenar = [];
            $hasilUrutan = [];
            $allJumlahBenar = [];
            $Sf = [];
            $Sfy = [];
            $Sfd = [];
            $Sy = [];
            $Sxy = [];

            $jumlah_Sf = 0;
            $jumlah_Sfy = 0;
            $jumlah_Sfd = 0;
            $jumlah_Sx = 0;
            $jumlah_Sy = 0;
            $jumlah_Sx2 = 0;
            $jumlah_Sxy = 0;
            $x50 = 0;
            $x0 = 0;

            $mean = 0;
            $parameter['panker'] = ['nilai' => 0, 'category'=>''];
            $parameter['tianker'] = ['nilai' => 0, 'category'=>''];
            $parameter['hanker'] = ['nilai' => 0, 'category'=>''];
            $parameter['janker'] = ['nilai' => 0, 'category'=>''];
            $y_tertinggi = 0;
            $y_terendah = 0;

            if(count($benarByColumn) > 0){
                foreach ($benarByColumn as $k => $val) {
                    $urutanBenar[$jumlahBenar.count($val)][] = 1;
                }
                //urutkan jumlah kolom yg benar dari yg paling bnyk benarnya
                krsort($urutanBenar);

                //$key adalah jumlah benarnya
                foreach ($urutanBenar as $key => $val) {
                    $allJumlahBenar[] = $key;
                    $f = count($val);
                    $hasilUrutan[] = [
                        'y' => $key,
                        'f' => $f,
                        'fy' => $f * $key,
                    ];
                    $Sf[] = $f;
                    $Sfy[] = ($f * $key);
                }
                $jumlah_Sf = $N = array_sum($Sf);
                $jumlah_Sfy = array_sum($Sfy);
                $mean = $jumlah_Sfy / $jumlah_Sf;

                foreach ($hasilUrutan as $key => $val) {
                    $this_d = abs($val['y']-$mean);
                    $hasilUrutan[$key]['d'] = $this_d;
                    $hasilUrutan[$key]['fd'] = $val['f'] * $this_d;
                    $Sfd[] = $val['f'] * $this_d;
                }
                $jumlah_Sfd = array_sum($Sfd);
                $dilewati = count($answerByCoordinate) - (count($salah) + count($benar));

                for ($i=1; $i <= count($this->column); $i++) { 
                    $jumlah_Sx += $i;
                    $Y = count($benarByColumn[$i]);
                    $chartLabel[] = $i;

                    if(array_key_exists($i, $benarByColumn)){
                        $jumlah_Sy += $Y;
                    }
                    if(array_key_exists($i, $countByColumn)){
                        $jumlahByColumn[$i] = count($countByColumn[$i]);
                    } else {
                        $jumlahByColumn[$i] = 0;
                    }
                 
                    $jumlah_Sx2 += pow($i, 2);
                    $jumlah_Sxy += ($i * $Y);
                }

                $b = (($N * $jumlah_Sxy) - ($jumlah_Sx * $jumlah_Sy)) / (($N * $jumlah_Sx2) - pow($jumlah_Sx, 2));
                $a = (($jumlah_Sy / $N) - ($b * ($jumlah_Sx / $N)) );
                $x50 = $a + ($b * 50);
                $x0 = $a + ($b * 0);

                $valuePanker = $mean;
                $valueTianker = count($salah);
                $valueHanker = $x50 - $x0;
                $valueJanker = round($jumlah_Sfd / $jumlah_Sf, 2);

                $reqKecepatan = new Request();
                $reqKecepatan->merge(['parameter' => 'kecepatan']);
                $reqKecepatan->merge(['education' => $participant->education]);
                $reqKecepatan->merge(['value' => $valuePanker]);
                $resKecepatan = $this->resultCategory($reqKecepatan);

                $reqKetelitian = new Request();
                $reqKetelitian->merge(['parameter' => 'ketelitian']);
                $reqKetelitian->merge(['education' => $participant->education]);
                $reqKetelitian->merge(['value' => $valueTianker]);
                $resKetelitian = $this->resultCategory($reqKetelitian);

                $reqKetahanan = new Request();
                $reqKetahanan->merge(['parameter' => 'ketahanan']);
                $reqKetahanan->merge(['education' => $participant->education]);
                $reqKetahanan->merge(['value' => $valueHanker]);
                $resKetahanan = $this->resultCategory($reqKetahanan);

                $reqKeajegan = new Request();
                $reqKeajegan->merge(['parameter' => 'keajegan']);
                $reqKeajegan->merge(['education' => $participant->education]);
                $reqKeajegan->merge(['value' => $valueJanker]);
                $resKeajegan = $this->resultCategory($reqKeajegan);

                $parameter['panker'] = ['nilai' => $valuePanker, 'category' => ($resKecepatan) ? $resKecepatan->category : ''];
                $parameter['tianker'] = ['nilai' => $valueTianker, 'category' => ($resKetelitian) ? $resKetelitian->category : ''];
                $parameter['hanker'] = ['nilai' => $valueHanker, 'category' => ($resKetahanan) ? $resKetahanan->category : ''];
                $parameter['janker'] = [
                    'range' => (max($allJumlahBenar) - min($allJumlahBenar)),
                    'av_dev' => $valueJanker,
                    'nilai' => $valueJanker, 
                    'category' => ($resKeajegan) ? $resKeajegan->category : ''
                ];
            }

            if($parameter['panker']['category']=='Kurang Sekali'){
                $statusKurangSekali[] = 1;
            } else if($parameter['panker']['category']=='Kurang'){
                $statusKurang[] = 1;
            }
            if($parameter['tianker']['category']=='Kurang Sekali'){
                $statusKurangSekaliKetelitian[] = 1;
            } else if($parameter['tianker']['category']=='Kurang'){
                $statusKurang[] = 1;
            }
            if($parameter['hanker']['category']=='Kurang Sekali'){
                $statusKurangSekali[] = 1;
            } else if($parameter['hanker']['category']=='Kurang'){
                $statusKurang[] = 1;
            }
            if($parameter['janker']['category']=='Kurang Sekali'){
                $statusKurangSekali[] = 1;
            } else if($parameter['janker']['category']=='Kurang'){
                $statusKurang[] = 1;
            }

            $result['summary']['hasil_tes'] = (count($statusKurang)>=2 || count($statusKurangSekali)>=2 || count($statusKurangSekaliKetelitian)>=1) ? 'Tidak Lolos' : 'Lolos';
            $result['summary']['benar'] = count($benar);
            $result['summary']['salah'] = count($salah);
            $result['summary']['lewat'] = $dilewati;
            $result['parameter'] = $parameter;
            $result['participant'] = $participant;

            $dataChart['label'] = $chartLabel;
            $dataChart['data'] = $jumlahByColumn;
            $result['chart'] = $this->configChartKraepelin($dataChart);

            DB::commit();
            $html = view('recruitment.monitoring.kraepelin_result', compact('result', 'listQuestions', 'columnQuestions', 'resultAnswer', 'type'))->render();

            if($type=='view'){
                $response = ['status' => true, 'message' => 'Berhasil', 'data' => $html];
                return Response::json($response);
            } else {
                $fileName = "Tes Kraepelin_".$participant->name.".pdf";
                $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape');
                return $pdf->download($fileName);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['status' => false, 'message' => $e->getMessage()];
            return Response::json($response);
        }
    }

    public function configChartKraepelin($parameter) {
        $implodeLabel = '['.implode(',',$parameter["label"]).']';
        $implodeValue = '['.implode(',',$parameter["data"]).']';
        $chartConfig = '{
            "type": "line",
            "data": {
                "labels": '.$implodeLabel.',
                "datasets": [{
                    data: '.$implodeValue.',
                    fill: false,
                    borderColor: "#A947FD",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                          display: true,
                          labelString: "Baris",
                        },
                        ticks: {
                            stepSize: 1,
                            suggestedMin: 1,
                            suggestedMax: 35
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                          display: true,
                          labelString: "Kolom",
                        },
                        ticks: {
                            stepSize: 1,
                            suggestedMin: 1,
                            suggestedMax: 50
                        }
                    }]
                }
            }
        }';
        //memakai quickchart agar bisa menampilkan grafik yg sudah berupa image agar bisa ditaruh di pdf utk didownload
        $urlEncode = 'https://quickchart.io/chart?w=auto&h=200&c='.urlencode($chartConfig);
        $return = 'data:image/jpg;base64,'.base64_encode(file_get_contents($urlEncode));
        return $return;
    }

}
