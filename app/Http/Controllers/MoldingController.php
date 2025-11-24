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
        ['no' => 3, 'approver_id' => 'PI2307060', 'approver_name' => 'Seiya Sekino', 'approver_email' => 'seiya.sekino@music.yamaha.com'],
        ['no' => 4, 'approver_id' => 'PI0703002', 'approver_name' => 'Susilo Basri Prasetyo', 'approver_email' => 'susilo.basri@music.yamaha.com'],
    ];

    protected $rank = [
        ['point' => 90, 'rank' => 'AA', 'keputusan' => 'Tidak perlu repair. Ditangani dengan maintenance rutin atau overhaul.'],
        ['point' => 70, 'rank' => 'A', 'keputusan' => 'Ada sebagian yang direpair.  Perlu direpair dengan schedule sesingkat mungkin menyesuaikan dengan schedule produksi.'],
        ['point' => 30, 'rank' => 'B', 'keputusan' => 'Ada beberapa titik yang perlu direpair secepatnya.Diskusi peremajaan'],
        ['point' => 0, 'rank' => 'C', 'keputusan' => 'Sulit melanjutkan proses produksi. Perlu peremajaan.'],
    ];

    public function indexMoldingDiagnoseList()
    {
        return view('molding.index_list');
    }

    function fetchMoldingDiagnoseList(Request $request) {
        $master_molding = DB::table('molding_diagnose_masters')
        ->select('id', 'fixed_asset_number', 'fixed_asset_name', 'vendor', 'standard_shot', 'total_shot', 'status')
        ->orderBy(db::raw('FIELD(status, "Butuh Pemeriksaan", null, "OK", "Sedang Diperiksa", "Sudah Diperiksa")'))
        ->get();

        return Response::json([
            'status' => 'success',
            'data' => $master_molding,
        ]);
    }

    public function indexMoldingDiagnoseFormList($asset_number = null)
    {
        $moldings = DB::table('molding_diagnose_masters')
        ->select('id', 'fixed_asset_number', 'fixed_asset_name')
        ->whereNull('deleted_at')
        ->get();

        return view('molding.index_form_list', compact('asset_number', 'moldings'));
    }

    function fetchMoldingDiagnoseFormList(Request $request)  {
        $master_molding = DB::table('molding_diagnose_forms')
        ->leftJoin('molding_diagnose_product_forms', 'molding_diagnose_forms.form_number', '=', 'molding_diagnose_product_forms.master_form_number')
        ->leftJoin('molding_diagnose_molding_forms', 'molding_diagnose_forms.form_number', '=', 'molding_diagnose_molding_forms.master_form_number')
        ->whereNull('molding_diagnose_forms.deleted_at')
        ->select('molding_diagnose_forms.form_number', 'molding_diagnose_forms.fixed_asset_number', 'molding_diagnose_forms.fixed_asset_name', 'molding_diagnose_forms.id_molding_check', 'molding_diagnose_forms.id_product_check', 'molding_diagnose_forms.total_score', 'molding_diagnose_forms.rank', 'molding_diagnose_forms.status', 'molding_diagnose_product_forms.id as form_product_id', 'molding_diagnose_molding_forms.id as form_molding_id', db::raw('DATE_FORMAT(molding_diagnose_forms.created_at,"%Y %b") as month'))
        ->get();

        return Response::json([
            'status' => 'success',
            'data' => $master_molding,
        ]);
    }

    function indexMoldProductCheckCreate($form_number = null) {
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
            $image1 = $request->file('photo_file_1');   
            $image_name1 = $request->form_number.'_'.$request->nomor . '_1.' . $image1->getClientOriginalExtension();
            $image1->move(public_path('workshop/molding/photo_product/ng'), $image_name1);

            $image_name2 = null;

            if ($request->file('photo_file_2')) {
                $image2 = $request->file('photo_file_2');   
                $image_name2 = $request->form_number.'_'.$request->nomor . '_2.' . $image2->getClientOriginalExtension();
                $image2->move(public_path('workshop/molding/photo_product/ng'), $image_name2);
            }

            $moldings = DB::table('molding_diagnose_product_forms')
            ->where('master_form_number', $request->form_number)
            ->select('fixed_asset_number', 'fixed_asset_name', 'vendor')
            ->first();

            // insert or update database
            DB::table('molding_diagnose_product_ngs')
            ->updateOrInsert(
                ['master_form_number' => $request->form_number, 'id_ng' => $request->nomor],
                [
                'master_form_number' => $request->form_number,
                'fixed_asset_number' => $moldings->fixed_asset_number,
                'fixed_asset_name' => $moldings->fixed_asset_name,
                'vendor' => $moldings->vendor,
                'id_ng' => $request->nomor,
                'ng_name' => $request->nama_ng,
                'photo1' => $image_name1,
                'photo2' => $image_name2,
                'check_by' => Auth::user()->name,
                'check_at' => date('Y-m-d H:i:s'),  
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
        ->select('molding_diagnose_molding_forms.id', 'molding_diagnose_molding_forms.master_form_number', 'molding_diagnose_molding_forms.fixed_asset_number', 'molding_diagnose_molding_forms.fixed_asset_name', 'molding_diagnose_molding_forms.points', 'check_by', 'check_at', 'ng_id', 'ng_name', 'diagnose_result', 'parts', 'photo1', 'photo2', 'item_number', 'item_name', 'note', 'deduction', 'molding_diagnose_molding_forms.created_at', 'molding_diagnose_molding_forms.updated_at')
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

    function saveMoldMoldingCheck(Request $request) {
        try {
            $mstr = DB::table('molding_diagnose_forms')
            ->where('form_number', $request->form_number)
            ->first();

            DB::table('molding_diagnose_molding_forms')->updateOrInsert(
                ['master_form_number' => $request->form_number],
                [
                    'master_form_number' => $request->form_number,
                    'fixed_asset_number' => $mstr->fixed_asset_number,
                    'fixed_asset_name' => $mstr->fixed_asset_name,
                    'vendor' => $mstr->vendor,
                    'points' => $request->total_poin,
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
                if($request->total_poin < $get_cek_product->points) {
                    $act_rank = null;
                    foreach ($this->rank as $key => $value) {
                        if($request->total_poin >= $value['point']) {
                            $act_rank = $value['rank'];
                        }
                    }

                    DB::table('molding_diagnose_forms')
                    ->where('form_number', $request->form_number)
                    ->update([
                        'mold_score' => $request->total_poin,
                        'total_score' => $request->total_poin,
                        'rank' => $act_rank,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    $act_rank = null;
                    foreach ($this->rank as $key => $value) {
                        if($get_cek_product->points >= $value['point']) {
                            $act_rank = $value['rank'];
                        }
                    }

                    DB::table('molding_diagnose_forms')
                    ->where('form_number', $request->form_number)
                    ->update([
                        'mold_score' => $request->total_poin,
                        'total_score' => $get_cek_product->points,
                        'rank' => $act_rank,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
                

            foreach ($request->data as $key => $value) {
                $photo1_name = null;
                $photo2_name = null;
                
                $photo1 = $request->file("data.$key.photo1");
                if($photo1) {
                    $photo1_name = $request->form_number . '_photo1_ng' . $value['ng_id'] . '_item' . $value['id_items'] . '.' . $photo1->getClientOriginalExtension();
                    $photo1->move(public_path('workshop/molding/photo_molding/ng'), $photo1_name);
                }

                $photo2 = $request->file("data.$key.photo2");
                if($photo2) {
                    $photo2_name = $request->form_number . '_photo2_ng' . $value['ng_id'] . '_item' . $value['id_items'] . '.' . $photo2->getClientOriginalExtension();
                    $photo2->move(public_path('workshop/molding/photo_molding/ng'), $photo2_name);
                }

                // if item_number and item_name is "null" 
                if($value['id_items'] == "null" && $value['item_name'] == "null") {
                    $value['id_items'] = null;
                    $value['item_name'] = null;
                }

                DB::table('molding_diagnose_molding_details')->updateOrInsert(
                    ['master_form_number' => $request->form_number, 'ng_id' => $value['ng_id'], 'item_number' => $value['id_items']],
                    [
                            'master_form_number' => $request->form_number,
                            'fixed_asset_number' => $mstr->fixed_asset_number,
                            'fixed_asset_name' => $mstr->fixed_asset_name,
                            'vendor' => $mstr->vendor,
                            'ng_id' => $value['ng_id'],
                            'ng_name' => $value['item_ng'],
                            'diagnose_result' => $value['hasil_diagnosa'],
                            'parts' => $value['part_checked'],
                            'item_number' => $value['id_items'],
                            'item_name' => $value['item_name'],
                            'note' => $value['rincian_lain'],
                            'photo1' => $photo1_name,
                            'photo2' => $photo2_name,
                            'deduction' => $value['pengurangan'],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->name
                        ]
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

    function saveProductCheckReal(Request $request) {
        try {
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

    function indexMoldProductReport($form_number) {
        $molding_name = DB::table('molding_diagnose_forms')
            ->leftJoin('molding_diagnose_product_forms', 'molding_diagnose_forms.form_number', '=', 'molding_diagnose_product_forms.master_form_number')
            ->where('molding_diagnose_forms.form_number', $form_number)
            ->select('molding_diagnose_forms.fixed_asset_name', 'molding_diagnose_forms.photo_product',
            'molding_diagnose_product_forms.points', 'molding_diagnose_product_forms.check_by', 'molding_diagnose_product_forms.check_at')
            ->first();

        $checklist = DB::table('molding_diagnose_product_masters')
            ->leftJoin(db::raw('(SELECT * FROM molding_diagnose_product_details WHERE master_form_number = "' . $form_number . '") as details'), 'molding_diagnose_product_masters.id', '=', 'details.ng_id')
            ->select('molding_diagnose_product_masters.id','item_ng','category_check','grouping', 'details.diagnose_result', 'details.deduction as actual_deduction')
            ->orderBy('id', 'asc')
            ->get();

        return view('molding.report.report_product_form', compact('form_number', 'molding_name', 'checklist'));
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
            ->select('molding_diagnose_forms.fixed_asset_name',
            'molding_diagnose_molding_forms.points', 'molding_diagnose_molding_forms.check_by', 'molding_diagnose_molding_forms.check_at')
            ->first();

        $checklist = DB::table('molding_diagnose_molding_masters')
            ->leftJoin(db::raw('(SELECT * FROM molding_diagnose_molding_details WHERE master_form_number = "' . $form_number . '") as details'), 'molding_diagnose_molding_masters.id', '=', 'details.ng_id')
            ->select('molding_diagnose_molding_masters.id','item_ng','grouping', 'details.diagnose_result', 'details.deduction as actual_deduction', 'molding_diagnose_molding_masters.daerah_ng', 'details.parts', 'molding_diagnose_molding_masters.item_check', db::raw('GROUP_CONCAT(details.item_name SEPARATOR ", ") as item_name'), 'details.note')
            ->groupBy('molding_diagnose_molding_masters.id','item_ng','grouping', 'details.diagnose_result', 'details.deduction', 'molding_diagnose_molding_masters.daerah_ng', 'details.parts', 'molding_diagnose_molding_masters.item_check', 'details.note')
            ->orderBy('id', 'asc')
            ->get();

        return view('molding.report.report_mold_form', compact('form_number', 'molding_name', 'checklist'));
    }

    function indexMoldMoldingReportNg($form_number) {
        $ngs = DB::table('molding_diagnose_molding_details')
            ->where('molding_diagnose_molding_details.master_form_number', $form_number)
            ->select('molding_diagnose_molding_details.fixed_asset_name', 'molding_diagnose_molding_details.photo1', 'molding_diagnose_molding_details.photo2',
            'molding_diagnose_molding_details.ng_id', 'molding_diagnose_molding_details.ng_name')
            ->orderBy('ng_id', 'asc')
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
        'molding_diagnose_forms.total_score', 'product_category', 'production_qty', 'production_date', 'production_period', 'penilaian_keseluruhan', 'pertimbangan_histori', 'tindakan_perbaikan', 'molding_diagnose_forms.created_by', db::raw('date_format(molding_diagnose_forms.created_at, "%d %b %Y") AS creat_at'), db::raw("SUBSTRING_INDEX(molding_diagnose_approvers.approver_name, ' ', 2) AS app_name"), db::raw('date_format(molding_diagnose_approvers.approve_at, "%d %b %Y") AS app_at'), db::raw('DATE_FORMAT(molding_diagnose_forms.production_date, "%d %b %Y") as prod_date'))
        ->orderBy('molding_diagnose_approvers.id', 'asc')
        ->get();

        return view('molding.report.report_evaluasi', compact('form_number', 'data_master'));
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
            $data_form_molding = DB::table('molding_diagnose_forms')
            ->where('form_number', $request->formNumber)
            ->select('molding_diagnose_forms.*', db::raw('DATE_FORMAT(molding_diagnose_forms.production_date, "%d %b %Y") as prod_date'))
            ->first();

            if($data_form_molding->status == 'Approval') {
                return response()->json([
                    'status' => false,
                    'message' => 'Form sudah pernah dikirim',
                ]);
            }

            DB::table('molding_diagnose_forms')
            ->where('form_number', $request->formNumber)
            ->update([
                'status' => 'Approval',
            ]);

            DB::table('molding_diagnose_molding_forms')
            ->where('master_form_number', $request->formNumber)
            ->update([
                'status' => 'Locked',
            ]);

            DB::table('molding_diagnose_product_forms')
            ->where('master_form_number', $request->formNumber)
            ->update([
                'status' => 'Locked',
            ]);

            // $data_form_molding = DB::table('molding_diagnose_forms')
            // ->where('form_number', $request->formNumber)
            // ->first();

            $data = [
                'data_form_molding' => $data_form_molding,
                'status' => 'approval_1',
            ];

            Mail::to($this->approver[0]['approver_email'])->bcc(['nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($data, 'molding_approval'));
            

            
            return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan dan dikirim',
                ]);
                
                // return view('molding.mails.mold_approval', compact('data_form_molding'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function indexShotList($month_range = null)
    {
        $master_molding = DB::table('molding_diagnose_masters')
        ->select('fixed_asset_number', 'fixed_asset_name')
        ->whereNull('molding_diagnose_masters.deleted_at')
        ->orderBy('fixed_asset_name', 'asc')
        ->get();

        return view('molding.index_shot_list', compact('master_molding'));
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


                $next_approver = ['yoga.karunia.perdana@music.yamaha.com', 'arief.asmo.saputro@music.yamaha.com'];
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
            $form_number = $request->formNumber;
    
            $data_form_molding = DB::table('molding_diagnose_forms')
            ->where('form_number', $form_number)
            ->select('molding_diagnose_forms.*', db::raw('DATE_FORMAT(molding_diagnose_forms.production_date, "%d %b %Y") as prod_date'))
            ->first();
    
            $get_app_pos = DB::table('molding_diagnose_approvers')
            ->where('form_number', $form_number)
            ->where(function ($query) use ($form_number) {
                $query->whereNull('approve_at')
                    ->orWhere(function ($query_or) use ($form_number) {
                        $query_or->where('form_number', $form_number)
                            ->whereIn('status', ['Holded']);
                    });
            })
            ->orderBy('id', 'asc')
            ->first();
    
            $data = [
                'data_form_molding' => $data_form_molding,
                'status' => 'approval_'. $get_app_pos->ordering,
            ];
    
            Mail::to($get_app_pos->approver_email)->bcc(['nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($data, 'molding_approval'));
            
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

    public function fetchMoldingShot(Request $request)
    {
        $date = new \DateTime($request->start_date);
        $week_start = $date->format("oW"); // ISO-8601 year + week number (mode 3)

        $date = new \DateTime($request->end_date);
        $week_end = $date->format("oW"); // ISO-8601 year + week number (mode 3)

        $data = DB::table('molding_diagnose_shots')
        ->whereNull('molding_diagnose_shots.deleted_at')
        ->where('molding_diagnose_shots.week_number', '>=', $week_start)
        ->where('molding_diagnose_shots.week_number', '<=', $week_end)
        ->select('fixed_asset_number', 'molding_name', 'week_number', 'total_shot', 'created_by', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get();

        $response = [
            'status' => true,
            'data' => $data
        ];

        return response()->json($response);
    }
}
