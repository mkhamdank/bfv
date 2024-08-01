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
use Response;
use Yajra\DataTables\Facades\DataTables;
// use DataTables;

class RecruitmentController extends Controller
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
        $this->column = ['aa','ab','ba','bb','ca','cb','da','db','ea','eb','fa','fb','ga','gb','ha','hb','ia','ib','ja','jb','ka','kb','la','lb','ma','mb','na','nb','oa','ob','pa','pb','qa','qb','ra','rb','sa','sb','ta','tb','ua','ub','va','vb','wa','wb','xa','xb','ya','yb'];
        $this->row = [35,34,33,32,31,30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1];
    }

    public function index()
    {
        $title = "Rekrutmen PT. YMPI";
        $title_jp = "Rekrutmen PT. YMPI";

        $compact = ['title','title_jp'];
        return view('recruitment.index', compact($compact));
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

    public function checkOpeningTest(Request $request)
    {
        $type = 'opening_kraepelin_test';
        $setting = DB::table('recruitment_settings')->where('type', $type)->first();
        $status = 'close';
        if($setting){
            $status = $setting->status;
        }
        return $status;
    }

    public function kraepelinTest(Request $request)
    {
        if(!session('session_ympi_recruitment')){
            return redirect(url('index/ympi_recruitment'));
        }

        $title = "Tes Kraepelin";
        $title_jp = $title;

        $getQuestion = DB::table('recruitment_kraepelin_tests')->whereNull('deleted_at')->orderBy('coordinate')->get();
        $listQuestions = [];
        foreach ($getQuestion as $key => $val) {
            $listQuestions[$val->coordinate] = $val->coordinate_value;
            $columnQuestions[$val->coordinate] = $val->column_number;
        }
        $participantId = session('session_ympi_recruitment')['participant_id'];

        $request->participant_id = $participantId;
        $request->test_date = date('Y-m-d');
        $testDone = $this->checkTest($request);

        $compact = ['title','title_jp', 'columnQuestions','listQuestions', 'testDone'];
        return view('recruitment.kraepelin', compact($compact));
    }

    public function checkParticipant(Request $request)
    {
        ini_set('max_execution_time', -1);
        $validatorMessages = [];
        $validatorRules = [];

        $validatorRules['card_id'] = 'required|numeric|digits:16';
        $validatorMessages['card_id.required'] = 'Mohon diisi';
        $validatorMessages['card_id.numeric'] = 'Harus diisi angka';
        $validatorMessages['card_id.digits'] = 'Harus diisi 16 digit';

        $validator = Validator::make($request->all(), $validatorRules, $validatorMessages);
        if ($validator->fails()) {
            $respond = ['status' => 'error', 'message' => $validator->errors()];
            return response()->json($respond, 400);
        }

        DB::beginTransaction();
        try{
            $cardId = $request->card_id;
            $checkParticipant = DB::table('recruitment_participants')->where('card_id', $cardId)->first();

            if($checkParticipant){
                $name = $checkParticipant->name;
                $address = $checkParticipant->address;
                $phone = $checkParticipant->phone;
                $education = $checkParticipant->education;
                $participantId = $checkParticipant->id;

                $sessionRecruitment = [
                    'participant_id' => $participantId,
                    'card_id' => $cardId,
                    'name' => $name,
                    'address' => $address,
                    'phone' => $phone,
                    'education' => $education,
                ];
                session(['session_ympi_recruitment' => $sessionRecruitment]);
            }
            DB::commit();
            $response = ['status' => true, 'message' => 'Berhasil', 'data' => $checkParticipant];
            return Response::json($response);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['status' => false, 'message' => $e->getMessage()];
            return Response::json($response);
        }
    }

    public function inputParticipant(Request $request)
    {
        ini_set('max_execution_time', -1);
        $validatorMessages = [];
        $validatorRules = [];

        $validatorRules['card_id'] = 'required';
        $validatorRules['name'] = 'required';
        $validatorRules['birth_place'] = 'required';
        $validatorRules['birth_date'] = 'required';
        $validatorRules['address'] = 'required';
        $validatorRules['phone'] = 'required';
        $validatorRules['education'] = 'required';
        $validatorRules['school'] = 'required';

        $validatorMessages['card_id.required'] = 'Mohon diisi';
        $validatorMessages['name.required'] = 'Mohon diisi';
        $validatorMessages['birth_place.required'] = 'Mohon diisi';
        $validatorMessages['birth_date.required'] = 'Mohon diisi';
        $validatorMessages['address.required'] = 'Mohon diisi';
        $validatorMessages['phone.required'] = 'Mohon diisi';
        $validatorMessages['education.required'] = 'Mohon diisi';
        $validatorMessages['school.required'] = 'Mohon diisi';

        $validator = Validator::make($request->all(), $validatorRules, $validatorMessages);
        if ($validator->fails()) {
            $respond = ['status' => 'error', 'message' => $validator->errors()];
            return response()->json($respond, 400);
        }

        DB::beginTransaction();
        try{
            $cardId = $request->card_id;
            $name = $request->name;
            $birthPlace = $request->birth_place;
            $birthDate = $request->birth_date;
            $address = $request->address;
            $phone = $request->phone;
            $education = $request->education;
            $school = $request->school;
            $testDate = $request->test_date;

            $dataParticipant = [
                'card_id' => $cardId,
                'name' => $name,
                'birth_place' => $birthPlace,
                'birth_date' => $birthDate,
                'address' => $address,
                'phone' => $phone,
                'education' => $education,
                'school' => $school,
            ];

            $checkParticipant = DB::table('recruitment_participants')->where('card_id', $cardId)->first();
            if(!$checkParticipant){
                $dataParticipant['created_at'] = date('Y-m-d H:i:s');
                $insertParticipant = DB::table('recruitment_participants')->insertGetId($dataParticipant);
                $participantId = $insertParticipant;
            } else {
                $dataParticipant['updated_at'] = date('Y-m-d H:i:s');
                $checkParticipant = DB::table('recruitment_participants')->where('card_id', $cardId)->update($dataParticipant);
                $participantId = $checkParticipant->id;
            }

            DB::commit();
            $sessionRecruitment = [
                'participant_id' => $participantId,
                'card_id' => $cardId,
                'name' => $name,
                'address' => $address,
                'phone' => $phone,
                'education' => $education,
                'school' => $school,
            ];
            session(['session_ympi_recruitment' => $sessionRecruitment]);
            $response = ['status' => true, 'message' => 'Berhasil', 'data' => $cardId];
            return Response::json($response);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['status' => false, 'message' => $e->getMessage()];
            return Response::json($response);
        }
    }

    public function kraepelinParticipantStore(Request $request)
    {
        ini_set('max_execution_time', -1);
        $validatorMessages = [];
        $validatorRules = [];

        $validatorRules['test_date'] = 'required';
        $validatorMessages['test_date.required'] = 'Mohon diisi';

        $validator = Validator::make($request->all(), $validatorRules, $validatorMessages);
        if ($validator->fails()) {
            $respond = ['status' => 'error', 'message' => $validator->errors()];
            return response()->json($respond, 400);
        }

        DB::beginTransaction();
        try{
            $testDate = $request->test_date;
            $testType = $request->test_type;
            $name = $request->name;

            $sessionIdTest = session('session_ympi_recruitment')['participant_id'];
            $checkTest = DB::table('recruitment_participant_tests')
                ->where('test_date', $testDate)
                ->where('test_type', $testType)
                ->where('participant_id', $sessionIdTest)
                ->first();

            if($checkTest){
                $checkTest = DB::table('recruitment_participant_tests')
                    ->where('test_date', $testDate)
                    ->where('test_type', $testType)
                    ->where('participant_id', $sessionIdTest)
                    ->update(['updated_at' => date('Y-m-d H:i:s'), 'updated_by' => @$sessionIdTest]);
            } else {
                $dataParticipantTest = [
                    'participant_id' => @$sessionIdTest,
                    'name' => $name,
                    'test_type' => $testType,
                    'test_date' => $testDate,
                    'created_by' => @$sessionIdTest,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $checkTest = DB::table('recruitment_participant_tests')->insert($dataParticipantTest);
            }

            DB::commit();
            $response = ['status' => true, 'message' => 'Berhasil', 'data' => $sessionIdTest];
            return Response::json($response);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['status' => false, 'message' => $e->getMessage()];
            return Response::json($response);
        }
    }

    public function kraepelinAnswerStore(Request $request)
    {
        ini_set('max_execution_time', -1);
        DB::beginTransaction();
        try{
            $dataAnswer = $request->data_answer ? json_decode($request->data_answer) : [];
            if(count($dataAnswer) > 0){
                foreach ($dataAnswer as $key => $val) {
                    $thisAnswer = json_decode($val[0]);
                    $dataResult = [];
                    foreach ($thisAnswer as $k => $item) {
                        if($item->name == 'answer'){
                            $answerValue = $item->value == '' ? '' : $item->value;
                        } 
                        if($item->name == 'coordinate'){
                            $coordinate = $item->value;
                        } 
                        if($item->name == 'column_number'){
                            $columnNumber = $item->value;
                        }
                        if($item->name == 'participant_id'){
                            $participantId = $item->value;
                        }
                        if($item->name == 'date'){
                            $date = $item->value;
                        }
                    }

                    $dataKraepelin = [
                        'coordinate'        => $coordinate,
                        'column_number'     => $columnNumber,
                        'answer'            => $answerValue,
                        'date'              => $date,
                        'participant_id'    => $participantId,
                    ];
                    
                    $checkAnswer = DB::table('recruitment_kraepelin_answers')
                        ->where('coordinate', $coordinate)
                        ->where('date', $date)
                        ->where('participant_id', $participantId)
                        ->first();

                    if($checkAnswer){
                        $dataKraepelin['updated_by'] = $participantId;
                        $dataKraepelin['updated_at'] = date('Y-m-d H:i:s');
                        $storeAnswer = DB::table('recruitment_kraepelin_answers')
                            ->where('coordinate', $coordinate)
                            ->where('date', $date)
                            ->where('participant_id', $participantId)
                            ->update($dataKraepelin);
                    } else {
                        $dataKraepelin['created_by'] = $participantId;
                        $dataKraepelin['created_at'] = date('Y-m-d H:i:s');
                        $storeAnswer = DB::table('recruitment_kraepelin_answers')->insert($dataKraepelin);
                        if(!$storeAnswer){
                            continue;
                        }
                    }    
                    DB::commit();
                }
            }

            $response = ['status' => true, 'message' => 'Berhasil', 'data' => 1];
            return Response::json($response);
        } catch (QueryException $e) {
            DB::rollback();
            $response = ['status' => false, 'message' => $e->getMessage(), 'data' => 1];
            return Response::json($response);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['status' => false, 'message' => $e->getMessage(), 'data' => 1];
            return Response::json($response);
        }
    }
}
