<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

use Response;

class MoldingController extends Controller
{
    protected $approver = [
        ['no' => 1, 'approver_id' => 'PI1108003', 'approver_name' => 'Andik Yayan Setyawan', 'approver_email' => 'andik.yayan@music.yamaha.com'],
        ['no' => 2, 'approver_id' => 'PI1106001', 'approver_name' => 'Darma Bagus Prasetya', 'approver_email' => 'darma.bagus@music.yamaha.com'],
        // ['no' => 3, 'approver_id' => 'PI2307060', 'approver_name' => 'Seiya Sekino', 'approver_email' => 'seiya.sekino@music.yamaha.com'],
        // ['no' => 4, 'approver_id' => 'PI0703002', 'approver_name' => 'Susilo Basri Prasetyo', 'approver_email' => 'susilo.basri@music.yamaha.com'],
    ];

    protected $rank = [
        ['point' => 90, 'rank' => 'AA', 'keputusan' => 'Tidak perlu repair. Ditangani dengan maintenance rutin atau overhaul.'],
        ['point' => 70, 'rank' => 'A', 'keputusan' => 'Ada sebagian yang direpair.  Perlu direpair dengan schedule sesingkat mungkin menyesuaikan dengan schedule produksi.'],
        ['point' => 30, 'rank' => 'B', 'keputusan' => 'Ada beberapa titik yang perlu direpair secepatnya.Diskusi peremajaan'],
        ['point' => 0, 'rank' => 'C', 'keputusan' => 'Sulit melanjutkan proses produksi. Perlu peremajaan.'],
    ];

    public function indexMoldingDiagnoseList()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }

        return view('molding.index_list');
    }

    public function indexMoldingMasterPart()
    {
        $masterParts = DB::table('molding_diagnose_molding_masters')
            ->select(
                'id',
                'item_ng as item_diagnosa',
                'daerah_ng as area',
                'item_check as jenis_ng',
                'grouping'
            )
            ->orderBy('grouping')
            ->orderBy('item_ng')
            ->orderBy('daerah_ng')
            ->get();

        $groupings = DB::table('molding_diagnose_molding_masters')
            ->whereNotNull('grouping')
            ->where('grouping', '<>', '')
            ->distinct()
            ->orderBy('grouping')
            ->pluck('grouping');

        return view('molding.index_master_part', compact('masterParts', 'groupings'));
    }

    public function storeMoldingMasterPart(Request $request)
    {
        $data = $request->validate([
            'item_diagnosa' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'jenis_ng' => ['required', 'string', 'max:255'],
            'grouping' => ['nullable', 'string', 'max:100'],
        ]);

        DB::table('molding_diagnose_molding_masters')->insert([
            'item_ng' => $data['item_diagnosa'],
            'daerah_ng' => $data['area'],
            'item_check' => $data['jenis_ng'],
            'grouping' => $data['grouping'] ?? null,
        ]);

        return redirect()->route('user.molding_master_part')->with('success', 'Master part molding berhasil ditambahkan.');
    }

    public function updateMoldingMasterPart(Request $request, $id)
    {
        $data = $request->validate([
            'item_diagnosa' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'jenis_ng' => ['required', 'string', 'max:255'],
            'grouping' => ['nullable', 'string', 'max:100'],
        ]);

        DB::table('molding_diagnose_molding_masters')->where('id', $id)->update([
            'item_ng' => $data['item_diagnosa'],
            'daerah_ng' => $data['area'],
            'item_check' => $data['jenis_ng'],
            'grouping' => $data['grouping'] ?? null,
        ]);

        return redirect()->route('user.molding_master_part')->with('success', 'Master part molding berhasil diperbarui.');
    }

    public function updateAllMoldingMasterParts(Request $request)
    {
        $data = $request->validate([
            'parts' => ['required', 'array', 'min:1'],
            'parts.*.item_diagnosa' => ['required', 'string', 'max:255'],
            'parts.*.area' => ['nullable', 'string', 'max:255'],
            'parts.*.jenis_ng' => ['nullable', 'string', 'max:255'],
            'parts.*.grouping' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            DB::transaction(function () use ($data) {
                foreach ($data['parts'] as $id => $part) {
                    DB::table('molding_diagnose_molding_masters')->where('id', $id)->update([
                        'item_ng' => $part['item_diagnosa'],
                        'daerah_ng' => $part['area'] ?? '',
                        'item_check' => $part['jenis_ng'] ?? '',
                        'grouping' => $part['grouping'] ?? null,
                    ]);
                }
            });
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()->withInput()->withErrors(
                'Gagal menyimpan perubahan master part. Silakan coba kembali.'
            );
        }

        return redirect()->route('user.molding_master_part')->with('success', 'Semua master part berhasil diperbarui.');
    }

    public function deleteMoldingMasterPart($id)
    {
        $hasDiagnoses = DB::table('molding_diagnose_molding_details')
            ->where('ng_id', $id)
            ->exists();

        if ($hasDiagnoses) {
            return redirect()->route('user.molding_master_part')->withErrors(
                'Master part sudah digunakan pada data diagnosa dan tidak dapat dihapus.'
            );
        }

        DB::table('molding_diagnose_molding_masters')->where('id', $id)->delete();

        return redirect()->route('user.molding_master_part')->with('success', 'Master part molding berhasil dihapus.');
    }

    function fetchMoldingDiagnoseList(Request $request) {
        $master_molding = DB::table('molding_diagnose_masters')
        ->select('id', 'fixed_asset_number', 'fixed_asset_name', 'vendor', 'standard_shot', 'total_shot', 'status', 'status_kawasan');

        if(Auth::user()->username != 'ympimis' && Auth::user()->username != 'molding_ympi'){
            $master_molding = $master_molding->where('vendor', Auth::user()->vendor_name);
        }

        $master_molding = $master_molding->orderBy(db::raw('FIELD(status, "Butuh Pemeriksaan", null, "OK", "Sedang Diperiksa", "Sudah Diperiksa")'))
        ->get();

        return Response::json([
            'status' => 'success',
            'data' => $master_molding,
        ]);
    }

    public function indexMoldingDiagnoseFormList($asset_number = null)
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        
        $moldings = DB::table('molding_diagnose_masters')
        ->select('id', 'fixed_asset_number', 'fixed_asset_name')
        ->whereNull('deleted_at')
        ->get();

        return view('molding.index_form_list', compact('asset_number', 'moldings'));

    }

    public function indexMoldingDiagnoseFormPeriodic($asset_number = null)
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        
        $moldings = DB::table('molding_diagnose_masters')
        ->select('id', 'fixed_asset_number', 'fixed_asset_name')
        ->whereNull('deleted_at')
        ->get();

        return view('molding.index_form_periodic', compact('asset_number', 'moldings'));

    }

    function fetchMoldingDiagnoseFormList(Request $request)  {
        $master_molding = DB::table('molding_diagnose_forms')
        ->leftJoin('molding_diagnose_masters', 'molding_diagnose_forms.fixed_asset_name', '=', 'molding_diagnose_masters.fixed_asset_name')
        ->leftJoin('molding_diagnose_product_forms', 'molding_diagnose_forms.form_number', '=', 'molding_diagnose_product_forms.master_form_number')
        ->leftJoin('molding_diagnose_molding_forms', 'molding_diagnose_forms.form_number', '=', 'molding_diagnose_molding_forms.master_form_number')
        ->whereNull('molding_diagnose_forms.deleted_at');

        if(Auth::user()->username != 'ympimis' && Auth::user()->username != 'molding_ympi'){
            $master_molding = $master_molding->where('molding_diagnose_masters.vendor', Auth::user()->vendor_name);
        }

        if($request->type && $request->type == 'periodic'){
            $master_molding = $master_molding->where('molding_diagnose_forms    .remark', $request->type);
        } else {
            $master_molding = $master_molding->whereNull('molding_diagnose_forms    .remark');
        }

        $master_molding = $master_molding->select('molding_diagnose_forms.form_number', 'molding_diagnose_forms.fixed_asset_number', 'molding_diagnose_forms.fixed_asset_name', 'molding_diagnose_forms.id_molding_check', 'molding_diagnose_forms.id_product_check', 'molding_diagnose_forms.total_score', 'molding_diagnose_forms.rank', 'molding_diagnose_forms.status', 'molding_diagnose_product_forms.id as form_product_id', 'molding_diagnose_molding_forms.id as form_molding_id', db::raw('DATE_FORMAT(molding_diagnose_forms.created_at,"%Y %b") as month'), 'molding_diagnose_masters.vendor', 'molding_diagnose_masters.location')
        ->get();

        return Response::json([
            'status' => 'success',
            'data' => $master_molding,
        ]);
    }

    private function isMoldingFormFullyApproved($formNumber)
    {
        $status = DB::table('molding_diagnose_forms')
            ->where('form_number', $formNumber)
            ->value('status');

        return strtolower((string) $status) === 'approved';
    }

    private function approvedFormEditResponse()
    {
        return response()->json([
            'status' => false,
            'message' => 'Form sudah fully approved dan hanya dapat dilihat melalui report.',
        ]);
    }

    function indexMoldProductCheckCreate($form_number = null) {
        if ($this->isMoldingFormFullyApproved($form_number)) {
            return redirect('/index/diagnose_molding/mold_product_check/report/' . $form_number);
        }

        $molding_name = DB::table('molding_diagnose_forms')
            ->where('form_number', $form_number)
            ->select('fixed_asset_name', 'photo_product')
            ->first();

        $master_check_list = DB::table('molding_diagnose_product_masters')
        ->select('id','item_ng','category_check','grouping')
        ->orderBy('id', 'asc')
        ->get();

        $penilaian_molding = DB::table('molding_diagnose_molding_forms')
        ->where('master_form_number', $form_number)
        ->select('master_form_number','points')
        ->first();

        $ranks = $this->rank;

        return view('molding.create_mold_check', compact('form_number', 'molding_name', 'master_check_list', 'penilaian_molding', 'ranks'));
    }

    function uploadProductImage(Request $request) {
        try {
            if ($this->isMoldingFormFullyApproved($request->form_number)) {
                return $this->approvedFormEditResponse();
            }

            $image = $request->file('image');
            $image_name = $request->form_number . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('workshop/molding/photo_product/main'), $image_name);

            // update database
        DB::table('molding_diagnose_forms')
            ->where('form_number', $request->form_number)
            ->update([
                'photo_product' => $image_name,
            ]);

        return response()->json([
            'status' => true,
            'image' => $image_name,
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    function fetchProductCheckList(Request $request) {
        $master_check_list = DB::table('molding_diagnose_product_masters')
        ->whereNull('deleted_at')
        ->select('id','item_ng','category_check','grouping', 'deduction')
        ->orderBy('id', 'asc')
        ->get();

        $actual_check_list = DB::table('molding_diagnose_product_forms')
        ->leftJoin('molding_diagnose_product_details', 'molding_diagnose_product_forms.master_form_number', '=', 'molding_diagnose_product_details.master_form_number')
        ->where('molding_diagnose_product_forms.master_form_number', $request->form_number)
        ->whereNull('molding_diagnose_product_forms.deleted_at')
        ->select('molding_diagnose_product_forms.id', 'molding_diagnose_product_forms.points', 'molding_diagnose_product_forms.status', 'molding_diagnose_product_details.id as detail_id', 'molding_diagnose_product_details.ng_id', 'molding_diagnose_product_details.ng_name', 'molding_diagnose_product_details.diagnose_result', 'molding_diagnose_product_details.deduction as actual_deduction')
        ->orderBy('molding_diagnose_product_details.ng_id', 'asc')
        ->get();

        return response()->json([
            'status' => true,
            'master_check_list' => $master_check_list,
            'actual_check_list' => $actual_check_list,
            ]);
    }

    function uploadPhotoNg(Request $request) {
        try {
            if ($this->isMoldingFormFullyApproved($request->form_number)) {
                return $this->approvedFormEditResponse();
            }

            $image1 = $request->file('photo_file_1');
            $existingNg = DB::table('molding_diagnose_product_ngs')
                ->where('master_form_number', $request->form_number)
                ->where('id_ng', $request->nomor)
                ->whereNull('deleted_at')
                ->first();

            if (!$image1 && (!$existingNg || !$existingNg->photo1)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Foto 1 wajib diisi',
                ]);
            }

            $image_name1 = null;
            if ($image1) {
                $image_name1 = $request->form_number.'_'.$request->nomor . '_1.' . $image1->getClientOriginalExtension();
                $image1->move(public_path('workshop/molding/photo_product/ng'), $image_name1);
            }

            $image_name2 = null;

            if ($request->file('photo_file_2')) {
                $image2 = $request->file('photo_file_2');   
                $image_name2 = $request->form_number.'_'.$request->nomor . '_2.' . $image2->getClientOriginalExtension();
                $image2->move(public_path('workshop/molding/photo_product/ng'), $image_name2);
            }

            $moldings = DB::table('molding_diagnose_forms')
            ->where('form_number', $request->form_number)
            ->select('fixed_asset_number', 'fixed_asset_name', 'vendor')
            ->first();

            // insert or update database
            $ngData = [
                'master_form_number' => $request->form_number,
                'fixed_asset_number' => $moldings->fixed_asset_number,
                'fixed_asset_name' => $moldings->fixed_asset_name,
                'vendor' => $moldings->vendor,
                'id_ng' => $request->nomor,
                'ng_name' => $request->nama_ng,
                'check_by' => Auth::user()->name,
                'check_at' => date('Y-m-d H:i:s'),  
                'created_by' => Auth::user()->name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($image_name1) {
                $ngData['photo1'] = $image_name1;
            }

            if ($image_name2) {
                $ngData['photo2'] = $image_name2;
            }

            DB::table('molding_diagnose_product_ngs')->updateOrInsert(
                ['master_form_number' => $request->form_number, 'id_ng' => $request->nomor],
                $ngData
            );

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan',
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    function fetchProductNg(Request $request) {
        $product_ng = DB::table('molding_diagnose_product_ngs')
        ->where('master_form_number', $request->form_number)
        ->where('id_ng', $request->id)
        ->whereNull('deleted_at')
        ->select('id', 'master_form_number', 'fixed_asset_number', 'fixed_asset_name', 'vendor', 'id_ng', 'ng_name', 'photo1', 'photo2', 'check_by', 'check_at', 'created_by', 'created_at', 'updated_at')
        ->get();

        return response()->json([
            'status' => true,
            'product_ng' => $product_ng,
        ]);
    }

    function deleteProductNg(Request $request) {
        try {
            $productNg = DB::table('molding_diagnose_product_ngs')
                ->where('id', $request->id)
                ->first();

            if ($productNg && $this->isMoldingFormFullyApproved($productNg->master_form_number)) {
                return $this->approvedFormEditResponse();
            }

            DB::table('molding_diagnose_product_ngs')
            ->where('id', $request->id)
            ->update([
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    function saveProductCheck(Request $request) {
        try {
            if ($this->isMoldingFormFullyApproved($request->form_number)) {
                return $this->approvedFormEditResponse();
            }

            $mstr = DB::table('molding_diagnose_forms')
            ->where('form_number', $request->form_number)
            ->first();

            if ($mstr) {
                DB::table('molding_diagnose_product_forms')
                ->where('master_form_number', $request->form_number)
                ->updateOrInsert(['master_form_number' => $request->form_number],[
                    'fixed_asset_number' => $mstr->fixed_asset_number,
                    'fixed_asset_name' => $mstr->fixed_asset_name,
                    'vendor' => $mstr->vendor,
                    'points' => $request->total_point,
                    'check_by' => Auth::user()->name,
                    'check_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                foreach ($request->ng_id as $key => $value) {
                    DB::table('molding_diagnose_product_details')->updateOrInsert(
                        ['master_form_number' => $request->form_number, 'ng_id' => $value],
                        [
                            'master_form_number' => $request->form_number,
                            'fixed_asset_number' => $mstr->fixed_asset_number,
                            'fixed_asset_name' => $mstr->fixed_asset_name,
                            'vendor' => $mstr->vendor,
                            'ng_id' => $value,
                            'ng_name' => $request->ng_name[$key],
                            'diagnose_result' => $request->ng_value_name[$key],
                            'deduction' => $request->ng_value[$key],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->name
                        ]
                    );
                }

                $get_cek_molding = DB::table('molding_diagnose_molding_forms')
                ->where('master_form_number', $request->form_number)
                ->select('points')
                ->first();

                if($get_cek_molding) {
                    if($request->total_point < $get_cek_molding->points) {
                        $act_rank = null;
                        foreach ($this->rank as $key => $value) {
                            if($request->total_point >= $value['point']) {
                                $act_rank = $value['rank'];
                            }
                        }

                        DB::table('molding_diagnose_forms')
                        ->where('form_number', $request->form_number)
                        ->update([
                            'product_score' => $request->total_point,
                            'total_score' => $request->total_point,
                            'rank' => $act_rank,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    } else {
                        $act_rank = null;
                        foreach ($this->rank as $key => $value) {
                            if($get_cek_molding->points >= $value['point']) {
                                $act_rank = $value['rank'];
                            }
                        }

                        DB::table('molding_diagnose_forms')
                        ->where('form_number', $request->form_number)
                        ->update([
                            'product_score' => $get_cek_molding->points,
                            'total_score' => $get_cek_molding->points,
                            'rank' => $act_rank,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Master form not found.',
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    function indexMoldMoldingCheckCreate($form_number = null) {
        if ($this->isMoldingFormFullyApproved($form_number)) {
            return redirect('/index/diagnose_molding/mold_molding_check/report/' . $form_number);
        }

        $molding_name = DB::table('molding_diagnose_forms')
            ->where('form_number', $form_number)
            ->select('fixed_asset_name')
            ->first();

        $master_check_list = DB::table('molding_diagnose_molding_masters')
        ->select('id','item_ng', 'item_check','daerah_ng','grouping', 'remark')
        ->orderBy('id', 'asc')
        ->get();

        $actual_check_list = DB::table('molding_diagnose_molding_forms')
        ->leftJoin('molding_diagnose_molding_details', 'molding_diagnose_molding_forms.master_form_number', '=', 'molding_diagnose_molding_details.master_form_number')
        ->where('molding_diagnose_molding_forms.master_form_number', $form_number)
        ->whereNull('molding_diagnose_molding_forms.deleted_at')
        ->select('molding_diagnose_molding_forms.id', 'molding_diagnose_molding_forms.master_form_number', 'molding_diagnose_molding_forms.fixed_asset_number', 'molding_diagnose_molding_forms.fixed_asset_name', 'molding_diagnose_molding_forms.points', 'check_by', 'check_at', 'ng_id', 'ng_name', 'diagnose_result', 'final_diagnose_result', 'parts', 'photo1', 'photo2', 'photo_process', 'handling_photo1', 'handling_photo2', 'handling_note', 'item_number', 'item_name', 'note', 'deduction', 'molding_diagnose_molding_forms.created_at', 'molding_diagnose_molding_forms.updated_at')
        ->get();

        $product_point = DB::table('molding_diagnose_product_forms')
        ->where('master_form_number', $form_number)
        ->select('points')
        ->first();

        $ranks = $this->rank;

        return view('molding.create_mold_molding_check', compact('form_number', 'molding_name', 'master_check_list', 'actual_check_list', 'ranks', 'product_point'));
    }

    function generateMoldProductCheckNew(Request $request) {
        $mon = date('ym');

        $last_data = db::table('molding_diagnose_forms')->where(db::raw('DATE_FORMAT(created_at,"%y%m")'), $mon)
        ->orderBy('form_number', 'desc')
        ->select('form_number')
        ->limit(1)
        ->first();

        if($last_data) {
            // Ambil huruf depan dan angka akhir
            $prefix = preg_replace('/\d+$/', '', $last_data->form_number);
            $angka = preg_replace('/^\D+/', '', $last_data->form_number);
    
            // Tambahkan angka
            $angka_baru = (int)$angka + 1;
    
            // Format ulang angka agar tetap punya padding nol (kalau perlu)
            $angka_baru = str_pad($angka_baru, strlen($angka), '0', STR_PAD_LEFT);
    
            // Gabungkan kembali
            $kode_baru = $prefix . $angka_baru;
        } else {
            $kode_baru = 'MLD'.$mon.'001';
        }


        $data_asset = db::table('molding_diagnose_masters')->where('fixed_asset_number', $request->asset_number)
        ->first();

        DB::table('molding_diagnose_forms')->insert([
            'form_number' => $kode_baru,
            'fixed_asset_number' => $request->asset_number,
            'fixed_asset_name' => $data_asset->fixed_asset_name,
            'vendor' => $data_asset->vendor,
            'created_by' => 'System',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        foreach ($this->approver as $key => $value) {
            DB::table('molding_diagnose_approvers')->insert([
                'form_number' => $kode_baru,
                'ordering' => $value['no'],
                'approver_id' => $value['approver_id'],
                'approver_name' => $value['approver_name'],
                'approver_email' => $value['approver_email'],
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect('/index/diagnose_molding/molding_form/'.$kode_baru);
    }

    public function openPeriodicForm(Request $request) {
        $request->validate([
            'asset_number' => ['required', 'string'],
            'period' => ['required', 'date_format:Y-m'],
            'form_type' => ['required', 'in:product,molding,evaluation'],
        ]);

        $assetNumber = $request->input('asset_number');
        $period = $request->input('period') . '-01';
        $formType = $request->input('form_type');

        $formNumber = DB::transaction(function () use ($assetNumber, $period) {
            $schedule = DB::table('molding_maintenance_schedules')
                ->where('period', $period)
                ->whereNull('deleted_at')
                ->lockForUpdate()
                ->first();

            if (!$schedule) {
                abort(404, 'Schedule molding tidak ditemukan untuk periode ini.');
            }

            $scheduledAsset = DB::table('molding_maintenance_schedule_items')
                ->where('schedule_id', $schedule->id)
                ->where('molding_id', $assetNumber)
                ->whereNull('deleted_at')
                ->first();

            if (!$scheduledAsset) {
                abort(404, 'Molding tidak ditemukan pada schedule periode ini.');
            }

            $existingForm = DB::table('molding_diagnose_forms')
                ->where('fixed_asset_number', $assetNumber)
                ->where('period', $period)
                ->where('remark', 'periodic')
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->first();

            if ($existingForm) {
                return $existingForm->form_number;
            }

            $asset = DB::table('molding_diagnose_masters')
                ->where('fixed_asset_number', $assetNumber)
                ->whereNull('deleted_at')
                ->first();

            if (!$asset) {
                abort(404, 'Data molding tidak ditemukan.');
            }

            $prefix = 'MLD' . date('ym', strtotime($period));
            $lastForm = DB::table('molding_diagnose_forms')
                ->where('form_number', 'like', $prefix . '%')
                ->orderBy('form_number', 'desc')
                ->value('form_number');
            $sequence = $lastForm ? (int) substr($lastForm, strlen($prefix)) + 1 : 1;
            $formNumber = $prefix . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
            $now = date('Y-m-d H:i:s');

            DB::table('molding_diagnose_forms')->insert([
                'form_number' => $formNumber,
                'fixed_asset_number' => $assetNumber,
                'fixed_asset_name' => $asset->fixed_asset_name,
                'vendor' => $asset->vendor,
                'period' => $period,
                'remark' => 'periodic',
                'created_by' => 'System',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($this->approver as $approver) {
                DB::table('molding_diagnose_approvers')->insert([
                    'form_number' => $formNumber,
                    'ordering' => $approver['no'],
                    'approver_id' => $approver['approver_id'],
                    'approver_name' => $approver['approver_name'],
                    'approver_email' => $approver['approver_email'],
                    'created_by' => 'System',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            return $formNumber;
        });

        $formRoutes = [
            'product' => 'mold_product_check/create',
            'molding' => 'mold_molding_check/create',
            'evaluation' => 'evaluation/edit',
        ];

        return redirect('/index/diagnose_molding/' . $formRoutes[$formType] . '/' . $formNumber);
    }

    function saveMoldMoldingCheck(Request $request) {
        try {
            if ($this->isMoldingFormFullyApproved($request->form_number)) {
                return $this->approvedFormEditResponse();
            }

            $request->validate([
                'data' => ['nullable', 'array'],
                'data.*.diagnosa_final' => ['nullable', 'in:OK,NG,OK Sementara'],
            ]);

            $mstr = DB::table('molding_diagnose_forms')
            ->where('form_number', $request->form_number)
            ->first();

            $finalDiagnoses = [];
            foreach ((array) $request->input('data', []) as $value) {
                $finalDiagnosis = $value['diagnosa_final'] ?? null;
                if (in_array($finalDiagnosis, ['OK', 'NG', 'OK Sementara'], true)) {
                    $finalDiagnoses[$value['ng_id']] = $finalDiagnosis;
                }
            }

            $groupings = DB::table('molding_diagnose_molding_masters')
                ->whereIn('id', array_keys($finalDiagnoses))
                ->pluck('grouping', 'id');
            $totalMoldingPoints = 100;
            foreach ($finalDiagnoses as $ngId => $finalDiagnosis) {
                $totalMoldingPoints -= $this->moldingDiagnosisDeduction($finalDiagnosis, $groupings[$ngId] ?? null);
            }
            $totalMoldingPoints = max(0, $totalMoldingPoints);

            DB::table('molding_diagnose_molding_forms')->updateOrInsert(
                ['master_form_number' => $request->form_number],
                [
                    'master_form_number' => $request->form_number,
                    'fixed_asset_number' => $mstr->fixed_asset_number,
                    'fixed_asset_name' => $mstr->fixed_asset_name,
                    'vendor' => $mstr->vendor,
                    'points' => $totalMoldingPoints,
                    'check_by' => Auth::user()->name,
                    'check_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->name
                ]
            );

            $get_cek_product = DB::table('molding_diagnose_product_forms')
            ->where('master_form_number', $request->form_number)
            ->select('points')
            ->first();

            // dd($get_cek_product);

            if($get_cek_product) {
                $moldingScore = $totalMoldingPoints;
                $totalScore = min($moldingScore, $get_cek_product->points);
                $act_rank = 'C';
                foreach ($this->rank as $value) {
                    if ($totalScore >= $value['point']) {
                        $act_rank = $value['rank'];
                        break;
                    }
                }

                DB::table('molding_diagnose_forms')
                    ->where('form_number', $request->form_number)
                    ->update([
                        'mold_score' => $moldingScore,
                        'total_score' => $totalScore,
                        'rank' => $act_rank,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }
                

            foreach ((array) $request->input('data', []) as $key => $value) {
                $photo1_name = null;
                $photo2_name = null;
                $photo_process_name = null;

                $photoNg1 = $request->file("data.$key.photo_ng_1");
                if ($photoNg1) {
                    $photo1_name = $request->form_number . '_photo1_ng' . $value['ng_id'] . '_item' . ($value['id_items'] ?? 'null') . '.' . $photoNg1->getClientOriginalExtension();
                    $photoNg1->move(public_path('workshop/molding/photo_molding/ng'), $photo1_name);
                }

                $photoNg2 = $request->file("data.$key.photo_ng_2");
                if ($photoNg2) {
                    $photo2_name = $request->form_number . '_photo2_ng' . $value['ng_id'] . '_item' . ($value['id_items'] ?? 'null') . '.' . $photoNg2->getClientOriginalExtension();
                    $photoNg2->move(public_path('workshop/molding/photo_molding/ng'), $photo2_name);
                }

                $photoProcess = $request->file("data.$key.photo_process");
                if ($photoProcess) {
                    $photo_process_name = $request->form_number . '_process_ng' . $value['ng_id'] . '_item' . ($value['id_items'] ?? 'null') . '.' . $photoProcess->getClientOriginalExtension();
                    $photoProcess->move(public_path('workshop/molding/photo_molding/ng'), $photo_process_name);
                }

                $handling_photo1_name = null;
                $handlingPhoto = $request->file("data.$key.photo_handling");
                if ($handlingPhoto) {
                    $handling_photo1_name = $request->form_number . '_handling_ng' . $value['ng_id'] . '_item' . ($value['id_items'] ?? 'null') . '.' . $handlingPhoto->getClientOriginalExtension();
                    $handlingPhoto->move(public_path('workshop/molding/photo_molding/ng'), $handling_photo1_name);
                }

                // if item_number and item_name is "null" 
                if($value['id_items'] == "null" && $value['item_name'] == "null") {
                    $value['id_items'] = null;
                    $value['item_name'] = null;
                }

                $detailData = [
                    'master_form_number' => $request->form_number,
                    'fixed_asset_number' => $mstr->fixed_asset_number,
                    'fixed_asset_name' => $mstr->fixed_asset_name,
                    'vendor' => $mstr->vendor,
                    'ng_id' => $value['ng_id'],
                    'ng_name' => $value['item_ng'],
                    'diagnose_result' => $value['hasil_diagnosa'],
                    'final_diagnose_result' => $value['diagnosa_final'] ?? null,
                    'parts' => $value['part_checked'],
                    'item_number' => $value['id_items'],
                    'item_name' => $value['item_name'],
                    'note' => $value['rincian_lain'],
                    'handling_note' => $value['handling_note'],
                    'deduction' => $this->moldingDiagnosisDeduction($value['diagnosa_final'] ?? null, $groupings[$value['ng_id']] ?? null),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->name
                ];

                if($photo1_name) {
                    $detailData['photo1'] = $photo1_name;
                }

                if($photo2_name) {
                    $detailData['photo2'] = $photo2_name;
                }

                if($photo_process_name) {
                    $detailData['photo_process'] = $photo_process_name;
                }

                if($handling_photo1_name) {
                    $detailData['handling_photo1'] = $handling_photo1_name;
                }

                DB::table('molding_diagnose_molding_details')->updateOrInsert(
                    ['master_form_number' => $request->form_number, 'ng_id' => $value['ng_id'], 'item_number' => $value['id_items']],
                    $detailData
                );
                }

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function moldingDiagnosisDeduction($diagnosis, $grouping)
    {
        if ($diagnosis === 'NG') {
            return $grouping === 'Main' ? 10 : 5;
        }

        if ($diagnosis === 'OK Sementara') {
            return $grouping === 'Main' ? 3 : 1;
        }

        return 0;
    }

    function saveProductCheckReal(Request $request) {
        try {
            if ($this->isMoldingFormFullyApproved($request->form_number)) {
                return $this->approvedFormEditResponse();
            }

            $mstr = DB::table('molding_diagnose_product_forms')
            ->where('master_form_number', $request->form_number)
            ->first();

            if ($mstr) {
                DB::table('molding_diagnose_product_forms')
                ->where('master_form_number', $request->form_number)
                ->update([
                    'points' => $request->total_point,
                    'check_by' => Auth::user()->name,
                    'check_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                foreach ($request->ng_id as $key => $value) {
                    DB::table('molding_diagnose_product_details')->updateOrInsert(
                        ['master_form_number' => $request->form_number, 'ng_id' => $value],
                        [
                            'master_form_number' => $request->form_number,
                            'fixed_asset_number' => $mstr->fixed_asset_number,
                            'fixed_asset_name' => $mstr->fixed_asset_name,
                            'vendor' => $mstr->vendor,
                            'ng_id' => $value,
                            'ng_name' => $request->ng_name[$key],
                            'diagnose_result' => $request->ng_value_name[$key],
                            'deduction' => $request->ng_value[$key],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->name
                        ]
                    );
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Master form not found.',
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function reportApprovalSignatures($formNumber)
    {
        return DB::table('molding_diagnose_approvers')
            ->where('form_number', $formNumber)
            ->orderBy('ordering', 'desc')
            ->select(
                'ordering',
                'status',
                db::raw("SUBSTRING_INDEX(approver_name, ' ', 2) as app_name"),
                db::raw('DATE_FORMAT(approve_at, "%d %b %Y") as app_at')
            )
            ->get();
    }

    function indexMoldProductReport($form_number) {
        $molding_name = DB::table('molding_diagnose_forms')
            ->leftJoin('molding_diagnose_product_forms', 'molding_diagnose_forms.form_number', '=', 'molding_diagnose_product_forms.master_form_number')
            ->where('molding_diagnose_forms.form_number', $form_number)
            ->select('molding_diagnose_forms.fixed_asset_name', 'molding_diagnose_forms.photo_product',
            'molding_diagnose_forms.created_by', db::raw('DATE_FORMAT(molding_diagnose_forms.created_at, "%d %b %Y") as form_created_at'),
            'molding_diagnose_product_forms.points', 'molding_diagnose_product_forms.check_by', 'molding_diagnose_product_forms.check_at')
            ->first();

        $checklist = DB::table('molding_diagnose_product_masters')
            ->leftJoin(db::raw('(SELECT * FROM molding_diagnose_product_details WHERE master_form_number = "' . $form_number . '") as details'), 'molding_diagnose_product_masters.id', '=', 'details.ng_id')
            ->select('molding_diagnose_product_masters.id','item_ng','category_check','grouping', 'details.diagnose_result', 'details.deduction as actual_deduction')
            ->orderBy('id', 'asc')
            ->get();

        $approvals = $this->reportApprovalSignatures($form_number);

        return view('molding.report.report_product_form', compact('form_number', 'molding_name', 'checklist', 'approvals'));
    }

    function indexMoldProductReportNg($form_number) {
        $ngs = DB::table('molding_diagnose_product_ngs')
            ->where('molding_diagnose_product_ngs.master_form_number', $form_number)
            ->select('molding_diagnose_product_ngs.fixed_asset_name', 'molding_diagnose_product_ngs.photo1', 'molding_diagnose_product_ngs.photo2',
            'molding_diagnose_product_ngs.id_ng', 'molding_diagnose_product_ngs.ng_name', 'molding_diagnose_product_ngs.check_by', 'molding_diagnose_product_ngs.check_at')
            ->orderBy('id_ng', 'asc')
            ->get()
            ->toArray();

        return view('molding.report.report_product_ng', compact('form_number', 'ngs'));
    }

    function indexMoldMoldingReport($form_number) {
        $molding_name = DB::table('molding_diagnose_forms')
            ->leftJoin('molding_diagnose_molding_forms', 'molding_diagnose_forms.form_number', '=', 'molding_diagnose_molding_forms.master_form_number')
            ->where('molding_diagnose_forms.form_number', $form_number)
            ->select('molding_diagnose_forms.fixed_asset_name', 'molding_diagnose_forms.created_by',
            db::raw('DATE_FORMAT(molding_diagnose_forms.created_at, "%d %b %Y") as form_created_at'),
            'molding_diagnose_molding_forms.points', 'molding_diagnose_molding_forms.check_by', 'molding_diagnose_molding_forms.check_at')
            ->first();

        $checklist = DB::table('molding_diagnose_molding_masters')
            ->leftJoin(db::raw('(SELECT * FROM molding_diagnose_molding_details WHERE master_form_number = "' . $form_number . '") as details'), 'molding_diagnose_molding_masters.id', '=', 'details.ng_id')
            ->select('molding_diagnose_molding_masters.id','item_ng','grouping', 'details.diagnose_result', 'details.deduction as actual_deduction', 'molding_diagnose_molding_masters.daerah_ng', 'details.parts', 'molding_diagnose_molding_masters.item_check', db::raw('GROUP_CONCAT(details.item_name SEPARATOR ", ") as item_name'), 'details.note')
            ->groupBy('molding_diagnose_molding_masters.id','item_ng','grouping', 'details.diagnose_result', 'details.deduction', 'molding_diagnose_molding_masters.daerah_ng', 'details.parts', 'molding_diagnose_molding_masters.item_check', 'details.note')
            ->addSelect('details.handling_note')
            ->groupBy('details.handling_note')
            ->orderBy('id', 'asc')
            ->get();

        $approvals = $this->reportApprovalSignatures($form_number);

        return view('molding.report.report_mold_form', compact('form_number', 'molding_name', 'checklist', 'approvals'));
    }

    function indexMoldMoldingReportNg($form_number) {
        $ngs = DB::table('molding_diagnose_molding_details')
            ->where('molding_diagnose_molding_details.master_form_number', $form_number)
            // ->whereIn('molding_diagnose_molding_details.diagnose_result', ['NG', 'OK Sementara'])
            ->select('molding_diagnose_molding_details.fixed_asset_name', 'molding_diagnose_molding_details.photo1', 'molding_diagnose_molding_details.photo2', 'molding_diagnose_molding_details.photo_process', 'molding_diagnose_molding_details.handling_photo1',
            'molding_diagnose_molding_details.ng_id', 'molding_diagnose_molding_details.ng_name', 'molding_diagnose_molding_details.item_number', 'molding_diagnose_molding_details.item_name', 'molding_diagnose_molding_details.final_diagnose_result')
            ->orderBy('ng_id', 'asc')
            ->orderBy('item_number', 'asc')
            ->get()
            ->toArray();

        return view('molding.report.report_mold_ng', compact('form_number', 'ngs'));
    }

    function indexEvaluationReport($form_number) {
        // $data_master = DB::table('molding_diagnose_forms')
        // ->where('molding_diagnose_forms.form_number', $form_number)
        // ->select('molding_diagnose_forms.fixed_asset_name', 'molding_diagnose_forms.rank',
        // 'molding_diagnose_forms.total_score', 'product_category', 'production_qty', 'production_date', 'production_period', 'molding_diagnose_forms.check_by', 'molding_diagnose_forms.check_at', 'penilaian_keseluruhan', 'pertimbangan_histori', 'tindakan_perbaikan', db::raw('DATE_FORMAT(molding_diagnose_forms.production_date, "%d %b %Y") as prod_date'))
        // ->first();
        $data_master = DB::table('molding_diagnose_forms')
        ->leftJoin('molding_diagnose_approvers', 'molding_diagnose_forms.form_number', '=', 'molding_diagnose_approvers.form_number')
        ->where('molding_diagnose_forms.form_number', $form_number)
        ->select('molding_diagnose_forms.fixed_asset_name', 'molding_diagnose_forms.rank',
        'molding_diagnose_forms.total_score', 'product_category', 'production_qty', 'production_date', 'production_period', 'penilaian_keseluruhan', 'pertimbangan_histori', 'tindakan_perbaikan', 'molding_diagnose_forms.created_by', db::raw('date_format(molding_diagnose_forms.created_at, "%d %b %Y") AS creat_at'), db::raw("SUBSTRING_INDEX(molding_diagnose_approvers.approver_name, ' ', 2) AS app_name"), db::raw('date_format(molding_diagnose_approvers.approve_at, "%d %b %Y") AS app_at'), 'molding_diagnose_approvers.status as approval_status', db::raw('DATE_FORMAT(molding_diagnose_forms.production_date, "%d %b %Y") as prod_date'))
        ->orderBy('molding_diagnose_approvers.id', 'asc')
        ->get();

        return view('molding.report.report_evaluasi', compact('form_number', 'data_master'));
    }

    private function generateMoldingReportPdf($formNumber)
    {
        $reports = [
            $this->indexEvaluationReport($formNumber),
            $this->indexMoldProductReport($formNumber),
            $this->indexMoldProductReportNg($formNumber),
            $this->indexMoldMoldingReport($formNumber),
            $this->indexMoldMoldingReportNg($formNumber),
        ];
        $headAssets = [];
        $sections = [];
        $previousLibxmlErrorHandling = libxml_use_internal_errors(true);

        try {
            foreach ($reports as $report) {
                $document = new \DOMDocument('1.0', 'UTF-8');
                $document->loadHTML($report->render(), LIBXML_NONET);

                foreach ($document->getElementsByTagName('head')->item(0)->childNodes as $node) {
                    $isStyle = $node->nodeName === 'style';
                    $isStylesheet = $node->nodeName === 'link' && strtolower($node->getAttribute('rel')) === 'stylesheet';
                    $isTailwind = $node->nodeName === 'script' && strpos($node->getAttribute('src'), 'cdn.tailwindcss.com') !== false;

                    if ($isStyle || $isStylesheet || $isTailwind) {
                        $asset = $document->saveHTML($node);
                        $headAssets[md5($asset)] = $asset;
                    }
                }

                $body = $document->getElementsByTagName('body')->item(0);
                $section = '';
                foreach ($body->childNodes as $node) {
                    if ($node->nodeName !== 'script') {
                        $section .= $document->saveHTML($node);
                    }
                }

                $sections[] = $section;
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousLibxmlErrorHandling);
        }

        $html = View::make('molding.report.report_pdf', [
            'headAssets' => $headAssets,
            'sections' => $sections,
        ])->render();

        $browsershot = Browsershot::html($html)
            ->format('A4')
            ->margins(2, 2, 2, 2)
            ->timeout(60)
            ->showBackground();

        if (env('BROWSERSHOT_PATH')) {
            $browsershot->setNodeBinary(env('BROWSERSHOT_PATH') . '/node');
            $browsershot->setNpmBinary(env('BROWSERSHOT_PATH') . '/npm');
        }

        $browsershot->setChromePath(env('PUPPETEER_CHROME_PATH'));

        $temporaryFile = tempnam(sys_get_temp_dir(), 'molding-report-');
        if ($temporaryFile === false) {
            throw new \RuntimeException('File sementara PDF tidak dapat dibuat.');
        }

        $temporaryPdf = $temporaryFile . '.pdf';
        if (!rename($temporaryFile, $temporaryPdf)) {
            unlink($temporaryFile);
            throw new \RuntimeException('File sementara PDF tidak dapat disiapkan.');
        }

        try {
            $browsershot->save($temporaryPdf);
            $pdf = file_get_contents($temporaryPdf);
            if ($pdf === false) {
                throw new \RuntimeException('File PDF sementara tidak dapat dibaca.');
            }

            return $pdf;
        } finally {
            if (is_file($temporaryPdf)) {
                unlink($temporaryPdf);
            }
        }
    }

    private function storeMoldingReportPdf($formNumber)
    {
        $safeFormNumber = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $formNumber);
        if ($safeFormNumber === '') {
            throw new \InvalidArgumentException('Nomor form tidak valid.');
        }

        $pdf = $this->generateMoldingReportPdf($formNumber);
        if (substr($pdf, 0, 5) !== '%PDF-') {
            throw new \RuntimeException('File PDF yang dihasilkan tidak valid.');
        }

        $directory = public_path('workshop/molding/reports');
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new \RuntimeException('Folder laporan PDF tidak dapat dibuat.');
        }

        $fileName = 'Laporan_Diagnosa_Molding_' . $safeFormNumber . '.pdf';
        $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;
        if (file_put_contents($filePath, $pdf, LOCK_EX) !== strlen($pdf)) {
            throw new \RuntimeException('File laporan PDF gagal disimpan.');
        }

        return url('workshop/molding/reports/' . $fileName);
    }

    private function getOrCreateMoldingReportPdfUrl($formNumber)
    {
        $safeFormNumber = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $formNumber);
        if ($safeFormNumber === '') {
            throw new \InvalidArgumentException('Nomor form tidak valid.');
        }

        $fileName = 'Laporan_Diagnosa_Molding_' . $safeFormNumber . '.pdf';
        $filePath = public_path('workshop/molding/reports/' . $fileName);

        if (!is_file($filePath)) {
            return $this->storeMoldingReportPdf($formNumber);
        }

        return url('workshop/molding/reports/' . $fileName);
    }

    function MoldProductReport($form_number) {
        // -------------------- PRODUCT NG -------------
        // $ngs = DB::table('molding_diagnose_product_ngs')
        //     ->where('molding_diagnose_product_ngs.master_form_number', $form_number)
        //     ->select('molding_diagnose_product_ngs.fixed_asset_name', 'molding_diagnose_product_ngs.photo1', 'molding_diagnose_product_ngs.photo2',
        //     'molding_diagnose_product_ngs.id_ng', 'molding_diagnose_product_ngs.ng_name', 'molding_diagnose_product_ngs.check_by', 'molding_diagnose_product_ngs.check_at')
        //     ->orderBy('id_ng', 'asc')
        //     ->get()
        //     ->toArray();

        // return view('molding.report.report_product_ng', compact('form_number', 'ngs'));

        // -------------------- MOLDING REPORT -------------
        // $molding_name = DB::table('molding_diagnose_forms')
        //     ->leftJoin('molding_diagnose_molding_forms', 'molding_diagnose_forms.form_number', '=', 'molding_diagnose_molding_forms.master_form_number')
        //     ->where('molding_diagnose_forms.form_number', $form_number)
        //     ->select('molding_diagnose_forms.fixed_asset_name',
        //     'molding_diagnose_molding_forms.points', 'molding_diagnose_molding_forms.check_by', 'molding_diagnose_molding_forms.check_at')
        //     ->first();

        // $checklist = DB::table('molding_diagnose_molding_masters')
        //     ->leftJoin(db::raw('(SELECT * FROM molding_diagnose_molding_details WHERE master_form_number = "' . $form_number . '") as details'), 'molding_diagnose_molding_masters.id', '=', 'details.ng_id')
        //     ->select('molding_diagnose_molding_masters.id','item_ng','grouping', 'details.diagnose_result', 'details.deduction as actual_deduction', 'molding_diagnose_molding_masters.daerah_ng', 'details.parts', 'molding_diagnose_molding_masters.item_check', db::raw('GROUP_CONCAT(details.item_name SEPARATOR ", ") as item_name'), 'details.note')
        //     ->groupBy('molding_diagnose_molding_masters.id','item_ng','grouping', 'details.diagnose_result', 'details.deduction', 'molding_diagnose_molding_masters.daerah_ng', 'details.parts', 'molding_diagnose_molding_masters.item_check', 'details.note')
        //     ->orderBy('id', 'asc')
        //     ->get();

        // return view('molding.report.report_mold_form', compact('form_number', 'molding_name', 'checklist'));

        // -------------------- MOLDING NG -------------

        // $ngs = DB::table('molding_diagnose_molding_details')
        //     ->where('molding_diagnose_molding_details.master_form_number', $form_number)
        //     ->select('molding_diagnose_molding_details.fixed_asset_name', 'molding_diagnose_molding_details.photo1', 'molding_diagnose_molding_details.photo2',
        //     'molding_diagnose_molding_details.ng_id', 'molding_diagnose_molding_details.ng_name')
        //     ->orderBy('ng_id', 'asc')
        //     ->get()
        //     ->toArray();

        // return view('molding.report.report_mold_ng', compact('form_number', 'ngs'));

        // -------------------- MOLDING EVALUASI -------------

        $data_master = DB::table('molding_diagnose_forms')
        ->where('molding_diagnose_forms.form_number', $form_number)
        ->select('molding_diagnose_forms.fixed_asset_name', 'molding_diagnose_forms.rank',
        'molding_diagnose_forms.total_score', 'product_category', 'production_qty', 'production_date', 'production_period', 'molding_diagnose_forms.check_by', 'molding_diagnose_forms.check_at')
        ->first();

        // return view('molding.report.report_evaluasi', compact('form_number', 'data_master'));
        $html = View::make('molding.report.report_evaluasi', compact('form_number', 'data_master'))->render();

        // 3. Define the settings for Browsershot
        // This is an optional step, but it allows for easy customization.
        $settings = [
            'format' => 'A4',
            'margins' => [0, 0, 0, 0],
            'timeout' => 60,
            // 'setNodeBinary' => 'C:\Program Files\nodejs\node.exe', // Add if needed
            // 'setChromePath' => 'C:\Program Files\Google\Chrome\Application\chrome.exe', // Add if needed
        ];

        // 4. Call the helper function to generate and download the PDF
        // The helper function handles saving, downloading, and cleanup.
        return generatePdfFromHtml($html, $settings, 'output_molding.pdf');
    }

    function updateShot(Request $request) {
        try {
            $data = DB::table('molding_diagnose_shots')
            ->where('fixed_asset_number', $request->asset_number)
            ->orderBy('id', 'desc')
            ->first();

            $shot = 0;
            if ($data) {
                $shot = $data->accumulative_shot;
            }

            $date = new \DateTime(date('Y-m-d'));
            $week_number = $date->format('oW');

            DB::table('molding_diagnose_shots')
            ->insert([
                'total_shot' => $request->shot,
                'fixed_asset_number' => $request->asset_number,
                'molding_name' => $request->molding_name,
                'week_number' => $week_number,
                'period' => $request->period.'-01',
                'accumulative_shot' => $shot + $request->shot,
                'created_by' => Auth::user()->name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('molding_diagnose_masters')
            ->where('fixed_asset_number', $request->asset_number)
            ->update([
                'total_shot' => $shot + $request->shot,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    function fetchShotList(Request $request) {
        try {
            $data = DB::table('molding_diagnose_shots')
            ->where('fixed_asset_number', $request->asset_number)
            ->orderBy('id', 'desc')
            ->get();

            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    function indexEvaluationEdit($form_number) {
        if ($this->isMoldingFormFullyApproved($form_number)) {
            return redirect('/index/diagnose_molding/evaluation/report/' . $form_number);
        }


        $data_master = DB::table('molding_diagnose_forms')
        ->leftJoin('molding_diagnose_masters', 'molding_diagnose_forms.fixed_asset_number', '=', 'molding_diagnose_masters.fixed_asset_number')
        ->where('molding_diagnose_forms.form_number', $form_number)
        ->select('molding_diagnose_forms.fixed_asset_name', 'molding_diagnose_forms.rank',
        'molding_diagnose_forms.total_score', 'product_category', 'production_qty', 'production_date', 'production_period', 'penilaian_keseluruhan', 'pertimbangan_histori', 'tindakan_perbaikan', db::raw('DATE_FORMAT(molding_diagnose_masters.acquired_date, "%d %b %Y") as acquired_date_text'), 'molding_diagnose_masters.acquired_date',
        DB::raw('TIMESTAMPDIFF(YEAR, molding_diagnose_masters.acquired_date, NOW()) as year_diff'),
        DB::raw('TIMESTAMPDIFF(MONTH, molding_diagnose_masters.acquired_date, NOW()) % 12 as month_diff')
        )
        ->first();

        return view('molding.evaluation_form', compact('form_number', 'data_master'));
    }

    function saveEvaluation(Request $request) {
        try {
            if ($this->isMoldingFormFullyApproved($request->form_number)) {
                return $this->approvedFormEditResponse();
            }

            DB::table('molding_diagnose_forms')
            ->where('form_number', $request->form_number)
            ->update([
                'penilaian_keseluruhan' => $request->penilaian,
                'pertimbangan_histori' => $request->pertimbangan,
                'tindakan_perbaikan' => $request->ide_tindakan,
                'production_date' => $request->tgl_mulai,
                'production_period' => $request->periode_produksi,
                'production_qty' => $request->qty_total,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    function postKerusakan(Request $request) {
        try {
            if ($request->hasFile('photo_file')) {
                $photo_files = $request->file('photo_file');
                $photo_file_names = [];
                foreach ($photo_files as $photo_file) {
                    $photo_file_name = $request->fa_number . '_' . date('Y-m-d H-i-s') . '_' . $photo_file->getClientOriginalExtension();
                    $photo_file->move(public_path('workshop/molding/photo_kerusakan'), $photo_file_name);
                    $photo_file_names[] = $photo_file_name;
                }
            }
            // insert to table molding_diagnose_troubles
            DB::table('molding_diagnose_troubles')
            ->insert([
                'fixed_asset_number' => $request->fa_number,
                'molding_name' => $request->molding_name,
                'tgl_kejadian' => $request->tanggal_kerusakan,
                'tgl_request' => $request->tanggal_target_kerusakan,
                'gejala' => $request->gejala,
                'total_shot' => $request->jml_shot_kerusakan,
                'selected_method' => $request->metode_dipilih,
                'reason_method' => $request->alasan_pemilihan,
                'before_repair' => $request->sebelum_repair,
                'after_repair' => $request->setelah_repair,
                'pemastian_mold' => $request->pemastian_mold,
                'pemastian_product' => $request->pemastian_product,
                'repair_method' => $request->metode_repair,
                'photo_kerusakan' => implode(',', $photo_file_names),
                'created_by' => Auth::user()->name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    function saveAndSendMoldingForm(Request $request) {
        try {
            $formNumber = $request->input('formNumber');
            if (!$formNumber) {
                return response()->json([
                    'status' => false,
                    'message' => 'Nomor form wajib diisi.',
                ]);
            }

            $data_form_molding = DB::table('molding_diagnose_forms')
            ->where('form_number', $formNumber)
            ->select('molding_diagnose_forms.*', db::raw('DATE_FORMAT(molding_diagnose_forms.production_date, "%d %b %Y") as prod_date'))
            ->first();

            if (!$data_form_molding) {
                return response()->json([
                    'status' => false,
                    'message' => 'Form tidak ditemukan',
                ]);
            }

            $pdfUrl = $this->storeMoldingReportPdf($formNumber);

            return response()->json([
                'status' => true,
                'message' => 'Laporan PDF berhasil disimpan. Email tidak dikirim.',
                'pdf_url' => $pdfUrl,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    function getDateRangesPhp5($year, $month)
    {
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $half = floor($totalDays / 2);

        // TANGGAL PENTING UNTUK RANGE
        $days = array(
            1,               // start range 1
            $half,           // end range 1
            $half + 1,       // start range 2
            $totalDays       // end range 2
        );

        $result = array(
            'Ymd' => array(),
            'YMd' => array(),
        );

        foreach ($days as $day) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);

            $result['Ymd'][] = date('Y-m-d', strtotime($date));
            $result['YMd'][] = date('d M y', strtotime($date));
        }

        return $result;
    }

    public function indexShotList($month_range = null)
    {
        $master_molding = DB::table('molding_diagnose_masters')
        ->select('fixed_asset_number', 'fixed_asset_name')
        ->whereNull('molding_diagnose_masters.deleted_at')
        ->orderBy('fixed_asset_name', 'asc')
        ->get();

       // ===============================
        // BULAN INI
        // ===============================
        $yearNow  = date('Y');
        $monthNow = date('m');

        // ===============================
        // BULAN KEMARIN
        // ===============================
        $lastMonth = strtotime('first day of last month');
        $yearLast  = date('Y', $lastMonth);
        $monthLast = date('m', $lastMonth);

        // ===============================
        // GABUNG JADI 1 ARRAY
        // ===============================
        $monthLastArr = $this->getDateRangesPhp5($yearLast, $monthLast);
        $monthNowArr  = $this->getDateRangesPhp5($yearNow, $monthNow);

        $dt = array_merge($monthLastArr['Ymd'], $monthNowArr['Ymd']);
        $dt_name = array_merge($monthLastArr['YMd'], $monthNowArr['YMd']);

        $output = array(
            'Ymd' => array_reverse($dt),
            'YMd' => array_reverse($dt_name),
        );

        $month_range = array_reverse($output);

        
        $grouped_month_range = array();
        $i = 0;
        while ($i < count($month_range['YMd'])) {
            $grouped_month_range[floor($i / 2) + 1][] = $month_range['YMd'][$i];
            $i++;
        }
        
        $month_range_grouped = $grouped_month_range;

        // dd($month_range_grouped);


        return view('molding.index_shot_list', compact('master_molding', 'month_range_grouped', 'month_range'));
    }

    public function indexTroubleList()
    {
        $master_molding = DB::table('molding_diagnose_masters')
        ->select('fixed_asset_number', 'fixed_asset_name')
        ->whereNull('molding_diagnose_masters.deleted_at')
        ->orderBy('fixed_asset_name', 'asc')
        ->get();
        
        return view('molding.index_trouble_list', compact('master_molding'));
    }

    public function approvalMoldingForm($status, $approval, $form_number, Request $request)
    {
        $data_form_molding = DB::table('molding_diagnose_forms')
        ->where('form_number', $form_number)
        ->select('molding_diagnose_forms.*', db::raw('DATE_FORMAT(molding_diagnose_forms.production_date, "%d %b %Y") as prod_date'))
        ->first();

        if($status == 'approve') {
            $status_cek = false;

            if($approval == 'approval_1') {
                $cek = DB::table('molding_diagnose_approvers')
                ->where('form_number', $form_number)
                ->where('approver_id', $this->approver[0]['approver_id'])
                ->whereNotNull('approve_at')
                ->first();

                if($cek) {
                    $status_cek = true;
                } else {
                    DB::table('molding_diagnose_approvers')
                    ->where('form_number', $form_number)
                    ->where('approver_id', $this->approver[0]['approver_id'])
                    ->update([
                        'status' => 'Approved',
                        'approve_at' => date('Y-m-d H:i:s'),
                    ]);

                    DB::table('molding_diagnose_forms')
                    ->where('form_number', $form_number)
                    ->update([
                        'status' => 'Approval'
                    ]);
                }


                $next_approver = $this->approver[1]['approver_email'];
                $status_new = 'approval_2';
            } else if ($approval == 'approval_2') {
                $cek = DB::table('molding_diagnose_approvers')
                ->where('form_number', $form_number)
                ->where('approver_id', $this->approver[1]['approver_id'])
                ->whereNotNull('approve_at')
                ->first();

                if($cek) {
                    $status_cek = true;
                } else {
                    DB::table('molding_diagnose_approvers')
                    ->where('form_number', $form_number)
                    ->where('approver_id', $this->approver[1]['approver_id'])
                    ->update([
                        'status' => 'Approved',
                        'approve_at' => date('Y-m-d H:i:s'),
                    ]);

                    DB::table('molding_diagnose_forms')
                    ->where('form_number', $form_number)
                    ->update([
                        'status' => 'Approval'
                    ]);
                }

                $next_approver = $this->approver[2]['approver_email'];
                $status_new = 'approval_3';
            } else if ($approval == 'approval_3') {
                $cek = DB::table('molding_diagnose_approvers')
                ->where('form_number', $form_number)
                ->where('approver_id', $this->approver[2]['approver_id'])
                ->whereNotNull('approve_at')
                ->first();

                if($cek) {
                    $status_cek = true;
                } else {
                    DB::table('molding_diagnose_approvers')
                    ->where('form_number', $form_number)
                    ->where('approver_id', $this->approver[2]['approver_id'])
                    ->update([
                        'status' => 'Approved',
                        'approve_at' => date('Y-m-d H:i:s'),
                    ]);

                    DB::table('molding_diagnose_forms')
                    ->where('form_number', $form_number)
                    ->update([
                        'status' => 'Approval'
                    ]);
                }

                $next_approver = $this->approver[3]['approver_email'];
                $status_new = 'approval_4';
            } else if ($approval == 'approval_4') {
                $cek = DB::table('molding_diagnose_approvers')
                ->where('form_number', $form_number)
                ->where('approver_id', $this->approver[3]['approver_id'])
                ->whereNotNull('approve_at')
                ->first();

                if($cek) {
                    $status_cek = true;
                } else {
                    DB::table('molding_diagnose_approvers')
                    ->where('form_number', $form_number)
                    ->where('approver_id', $this->approver[3]['approver_id'])
                    ->update([
                        'status' => 'Approved',
                        'approve_at' => date('Y-m-d H:i:s'),
                    ]);

                    DB::table('molding_diagnose_forms')
                    ->where('form_number', $form_number)
                    ->update([
                        'status' => 'Approved'
                    ]);
    
                    DB::table('molding_diagnose_masters')
                    ->where('fixed_asset_number', $data_form_molding->fixed_asset_number)
                    ->update([
                        'status' => 'Sudah Diperiksa'
                    ]);
                }


                $next_approver = ['yoga.karunia.perdana@music.yamaha.com', 'arief.asmo.saputro@music.yamaha.com', 'andik.yayan@music.yamaha.com'];
                $status_new = 'approval_5';
            }

            $app = new \stdClass();
            $app->approver_id = "";

            if($status_cek) {
                $data = [
                    'data_form_molding' => $data_form_molding,
                    'status' => 'Already Approved',
                    'approval' => $app
                ];

                return view('molding.report.mold_approval_complete', $data);
            }
    

            $data = [
                'data_form_molding' => $data_form_molding,
                'status' => $status_new,
                'approval' => $app
            ];

            $data['report_pdf_url'] = $this->getOrCreateMoldingReportPdfUrl($form_number);
    
            Mail::to($next_approver)->bcc(['nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($data, 'molding_approval'));
    
            return view('molding.report.mold_approval_complete', $data);
        } else if ($status == 'hold') {
            $app = DB::table('molding_diagnose_approvers')
            ->where('form_number', $form_number)
            ->whereNull('approve_at')
            ->where('ordering', explode('_', $approval)[1])
            ->first();

            if($app) {
                $data = [
                    'data_form_molding' => $data_form_molding,
                    'status' => 'hold_view',
                    'approval' => $app
                ];
            } else {
                $app = new \stdClass();
                $app->approver_id = "Already Approve";

                $data = [
                    'data_form_molding' => $data_form_molding,
                    'status' => 'hold_view',
                    'approval' => $app
                ];
            }


            return view('molding.report.mold_approval_complete', $data);
        } else if ($status == 'reject') {
           $app = DB::table('molding_diagnose_approvers')
            ->where('form_number', $form_number)
            ->whereNull('approve_at')
            ->where('ordering', explode('_', $approval)[1])
            ->first();

            if($app) {
                $data = [
                    'data_form_molding' => $data_form_molding,
                    'status' => 'reject_view',
                    'approval' => $app
                ];
            } else {
                $app = new \stdClass();
                $app->approver_id = "Already Approve";

                $data = [
                    'data_form_molding' => $data_form_molding,
                    'status' => 'reject_view',
                    'approval' => $app
                ];
            }

            return view('molding.report.mold_approval_complete', $data);
        } else if ($status == 'hold_comment') {
            try {
                DB::table('molding_diagnose_approvers')
                ->where('form_number', $form_number)
                ->whereNull('approve_at')
                ->where('approver_id', $approval)
                ->update([
                    'approve_at' => date('Y-m-d H:i:s'),
                    'comment' => $request->comment,
                    'status' => 'Holded'
                ]);

                DB::table('molding_diagnose_forms')
                ->where('form_number', $form_number)
                ->update([
                    'status' => 'Holded'
                ]);

                $update = DB::table('molding_diagnose_approvers')
                ->where('form_number', $form_number)
                ->where('approver_id', $approval)
                ->first();

                $data = [
                    'data_form_molding' => $data_form_molding,
                    'status' => 'holded',
                    'approval' => $update->approver_name,
                    'comment' => $request->comment
                ];

                $data['report_pdf_url'] = $this->getOrCreateMoldingReportPdfUrl($form_number);

                $next_approver = ['yoga.karunia.perdana@music.yamaha.com', 'arief.asmo.saputro@music.yamaha.com'];
        
                Mail::to($next_approver)->bcc(['nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($data, 'molding_approval'));

                return response()->json([
                    'status' => true,
                    'message' => 'Success',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        } else if($status == 'reject_comment') {
            try {
                 $cek = DB::table('molding_diagnose_approvers')
                ->where('form_number', $form_number)
                ->where('approver_id', $this->approver[3]['approver_id'])
                ->whereNotNull('approve_at')
                ->first();

                if($cek) {
                    $status_cek = true;
                } else {
                    DB::table('molding_diagnose_approvers')
                    ->where('form_number', $form_number)
                    ->where('approver_id', $this->approver[3]['approver_id'])
                    ->update([
                        'status' => 'Rejected',
                        'approve_at' => date('Y-m-d H:i:s'),
                        'comment' => $request->comment
                    ]);

                    DB::table('molding_diagnose_forms')
                    ->where('form_number', $form_number)
                    ->update([
                        'status' => 'Rejected'
                    ]);
                }

            $next_approver = ['yoga.karunia.perdana@music.yamaha.com', 'arief.asmo.saputro@music.yamaha.com'];
            $status_new = 'rejected';    

            $reject = DB::table('molding_diagnose_approvers')
                ->where('form_number', $form_number)
                ->where('approver_id', $approval)
                ->first();

            $data = [
                'data_form_molding' => $data_form_molding,
                'status' => $status_new,
                'approval' => $reject->approver_name,
                'comment' => $request->comment
            ];

            $data['report_pdf_url'] = $this->getOrCreateMoldingReportPdfUrl($form_number);
    
            Mail::to($next_approver)->bcc(['nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($data, 'molding_approval'));
    
            return response()->json([
                'status' => true,
                'message' => 'Success',
            ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }

    public function sendMoldingForm(Request $request)
    {
        try {
            $form_number = $request->input('formNumber');
            if (!$form_number) {
                return response()->json([
                    'status' => false,
                    'message' => 'Nomor form wajib diisi.',
                ]);
            }

            $data_form_molding = DB::table('molding_diagnose_forms')
            ->where('form_number', $form_number)
            ->select('molding_diagnose_forms.*', db::raw('DATE_FORMAT(molding_diagnose_forms.production_date, "%d %b %Y") as prod_date'))
            ->first();

            if (!$data_form_molding) {
                return response()->json([
                    'status' => false,
                    'message' => 'Form tidak ditemukan.',
                ]);
            }

            $reportPdfUrl = $this->getOrCreateMoldingReportPdfUrl($form_number);

            $delivery = DB::transaction(function () use ($form_number, $data_form_molding, $reportPdfUrl) {
                $get_app_pos = DB::table('molding_diagnose_approvers')
                    ->where('form_number', $form_number)
                    ->whereIn('status', ['Holded', 'Hold'])
                    ->orderBy('ordering', 'asc')
                    ->lockForUpdate()
                    ->first();
                $isHeld = (bool) $get_app_pos;

                if (!$get_app_pos) {
                    $get_app_pos = DB::table('molding_diagnose_approvers')
                        ->where('form_number', $form_number)
                        ->whereNull('approve_at')
                        ->orderBy('ordering', 'asc')
                        ->lockForUpdate()
                        ->first();
                }

                if (!$get_app_pos) {
                    return null;
                }

                if ($isHeld) {
                    $now = date('Y-m-d H:i:s');
                    DB::table('molding_diagnose_approvers')
                        ->where('id', $get_app_pos->id)
                        ->update([
                            'status' => null,
                            'approve_at' => null,
                            'updated_at' => $now,
                        ]);

                    DB::table('molding_diagnose_forms')
                        ->where('form_number', $form_number)
                        ->update([
                            'status' => 'Approval',
                            'updated_at' => $now,
                        ]);
                }

                $data = [
                    'data_form_molding' => $data_form_molding,
                    'status' => 'approval_' . $get_app_pos->ordering,
                    'report_pdf_url' => $reportPdfUrl,
                ];

                Mail::to($get_app_pos->approver_email)
                    ->bcc(['nasiqul.ibat@music.yamaha.com'])
                    ->send(new SendEmail($data, 'molding_approval'));

                return [
                    'is_held' => $isHeld,
                    'approver_name' => $get_app_pos->approver_name,
                ];
            });

            if (!$delivery) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada approval yang menunggu untuk form ini.',
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => $delivery['is_held']
                    ? 'Email dikirim ulang ke ' . $delivery['approver_name'] . '. Status form kembali ke Approval.'
                    : 'Email approval berhasil dikirim ke ' . $delivery['approver_name'] . '.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function fetchMoldingShot(Request $request)
    {
        $date = new \DateTime($request->start_date);
        $week_start = $date->format("oW"); // ISO-8601 year + week number (mode 3)

        $date = new \DateTime($request->end_date);
        $week_end = $date->format("oW"); // ISO-8601 year + week number (mode 3)

        $data = DB::table('molding_diagnose_shots')
        ->leftJoin('molding_diagnose_masters', 'molding_diagnose_shots.fixed_asset_number', '=', 'molding_diagnose_masters.fixed_asset_number')
        ->whereNull('molding_diagnose_shots.deleted_at')
        ->where('molding_diagnose_shots.created_at', '>=', $request->start_date)
        ->where('molding_diagnose_shots.created_at', '<=', $request->end_date);

        if(Auth::user()->username != 'ympimis' && Auth::user()->username != 'molding_ympi'){
            $data = $data->where('molding_diagnose_masters.vendor', Auth::user()->vendor_name);
        }

        $data = $data->select('molding_diagnose_shots.fixed_asset_number', 'molding_name', 'week_number', 'molding_diagnose_shots.total_shot', 'accumulative_shot', 'molding_diagnose_shots.created_by', 'molding_diagnose_shots.created_at', db::raw('DATE_FORMAT(molding_diagnose_shots.created_at, "%Y-%m-%d") as create_date'), db::raw('DATE_FORMAT(molding_diagnose_shots.created_at, "%d %b %y") as tgl_buat'))
        ->orderBy('molding_diagnose_shots.created_at', 'desc')
        ->get();

        $response = [
            'status' => true,
            'data' => $data
        ];

        return response()->json($response);
    }

    public function getPengiriman(Request $request)
    {
        $data = DB::table('molding_sends')
        ->where('fixed_asset_number', $request->no_fixed_asset)
        ->get();

        $response = [
            'status' => true,
            'datas' => $data
        ];

        return response()->json($response);
    }

    public function postPengiriman(Request $request)
    {
        try {
            $document = [];

            if($request->surat_jalan_buat_kirim) {
                $file = $request->surat_jalan_buat_kirim;
                $extension = $file->getClientOriginalExtension();
                $file_name = 'surat_jalan_'.time().'.'.$extension;
                $file->move(public_path('workshop/molding/file_pengiriman/surat_jalan'), $file_name);
                $document[] = ['surat_jalan' => $file_name];
            }

            if($request->foto_packing_buat_kirim) {
                $file = $request->foto_packing_buat_kirim;
                $extension = $file->getClientOriginalExtension();
                $file_name = 'foto_packing_'.time().'.'.$extension;
                $file->move(public_path('workshop/molding/file_pengiriman/foto_packing'), $file_name);
                $document[] = ['foto_packing' => $file_name];
            }

            if($request->bc_27_buat_kirim) {
                $file = $request->bc_27_buat_kirim;
                $extension = $file->getClientOriginalExtension();
                $file_name = 'bc_27_'.time().'.'.$extension;
                $file->move(public_path('workshop/molding/file_pengiriman/bc_27'), $file_name);
                $document[] = ['bc_27' => $file_name];
            }

            $document = json_encode($document);
            // insert into molding_diagnose_sends
            DB::table('molding_sends')->insert([
                'fixed_asset_number' => $request->fa_number,
                'molding_name' => $request->nama_molding,
                'document' => $document,
                'approved_by' => 'PI1908032/Erlangga Kharisma',
                'created_by' => $request->createdBy,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function postPengirimanKirim(Request $request)
    {
        try {
            DB::table('molding_sends')
            ->where('id', $request->id)
            ->update([
                'status' => 'Approval',
                'tgl_pengiriman' => $request->tgl_kirim
            ]);

            $data_molding = DB::table('molding_sends')
            ->leftJoin('molding_diagnose_masters', 'molding_sends.fixed_asset_number', '=', 'molding_diagnose_masters.fixed_asset_number')
            ->where('molding_sends.id', $request->id)
            ->select('molding_sends.*', 'molding_diagnose_masters.vendor', 'molding_diagnose_masters.status_kawasan')
            ->first();

            $data = [
                'data_form_molding' => $data_molding,
                'status' => 'approval'
            ];

            Mail::to('erlangga.kharisma@music.yamaha.com')->bcc(['nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($data, 'molding_pengiriman'));

            return response()->json([
                'status' => true,
                'message' => 'Successfully Send Email',
                'data' => $data_molding
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function approvalPengiriman($status, $id)
    {
        try {
            if($status == "approve") {
                DB::table('molding_sends')
                ->where('id', $id)
                ->update([
                    'status' => 'Approved',
                    'approved_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }

            $data_molding = DB::table('molding_sends')
            ->leftJoin('molding_diagnose_masters', 'molding_sends.fixed_asset_number', '=', 'molding_diagnose_masters.fixed_asset_number')
            ->where('molding_sends.id', $id)
            ->select('molding_sends.*', 'molding_diagnose_masters.vendor', 'molding_diagnose_masters.status_kawasan')
            ->first();

            $data = [
                'data_form_molding' => $data_molding,
                'status' => $status
            ];

            return view('molding.report.mold_send_complete', $data);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function indexMoldingDiagnoseMonitoring()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }

        return view('molding.index_monitoring');
    }

    public function fetchMoldingDiagnoseMonitoring()
    {

    }

    public function indexCheckMoldingNew()
	{
		$title = 'Monitoring Molding Vendor (ARISA)';
		$title_jp = '??';

		$molding = db::table('pe_molding_masters')
			->select('id', 'molding_name', 'mold_number', 'molding_type')
			->get();

		return view(
			'molding.index_monitoring_new',
			array(
				'title' => $title,
				'title_jp' => $title_jp,
				'moldings' => $molding,
			)
		)->with('page', 'Workshop Audit Molding');
	}

    public function fetchCheckMoldingNew(Request $request)
    {
        $schedules = db::table('molding_diagnose_schedules')
        ->whereNull('deleted_at')
        ->select(db::raw('DATE_FORMAT(period, "%Y %b") as period_name'), 'fixed_asset_number', 'molding_name', 'status')
        ->orderBy('period', 'ASC')
        ->get();
        
        return response()->json([
            'status' => true,
            'schedules' => $schedules
        ]);
    }
}
