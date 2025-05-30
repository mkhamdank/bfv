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

class RioController extends Controller
{
    function indexrio(){
       return view('ibat.index')->with('page', 'Rio');
    }

    public function index_vendor_registration()
    {
        $title = "Kelengkapan Data Vendor PT. YMPI";
        $title_jp = "";

        $kbli = DB::table('vendor_registration_kblis')->get();

        return view('vendor_registration.index', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'kbli' => $kbli
        ))->with('page', 'Vendor Registration');
    }


    public function ympi_vendor_registration()
    {
        $title = "Registrasi Data Vendor PT. YMPI";
        $title_jp = "";

        $kbli = DB::table('vendor_registration_kblis')->get();

        return view('vendor_registration.index_registration', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'kbli' => $kbli
        ))->with('page', 'Vendor Registration');
    }

    public function post_vendor_registration(Request $request)
    {
        try {
            $namaPerusahaan = $request->input('nama_perusahaan');

            function storeFile($request, $fieldName, $folder, $namaPerusahaan) {
                if ($request->hasFile($fieldName)) {
                    $fileName = $namaPerusahaan . '_' . $fieldName . '.' . $request->file($fieldName)->getClientOriginalExtension();
                    $request->file($fieldName)->move("files/vendor_registration/$folder", $fileName);
                    return $fileName;
                }
                return null;
            }

            $data = [
                'unique_code' => $request->input('unique_code'),
                'badan_usaha' => $request->input('badan_usaha'),
                'nama_perusahaan' => $namaPerusahaan,
                'pimpinan_perusahaan' => $request->input('pimpinan_perusahaan'),
                'file_profil_perusahaan' => storeFile($request, 'file_profil_perusahaan', 'company_profile', $namaPerusahaan),
                'aktivitas_bisnis' => $request->input('aktivitas_bisnis'),
                'pertanyaan_akta_pendirian' => $request->input('pertanyaan_akta_pendirian'),
                'file_akta_pendirian' => storeFile($request, 'file_akta_pendirian', 'akta_pendirian', $namaPerusahaan),
                'alasan_akta_pendirian' => $request->input('alasan_akta_pendirian'),
                'alamat_perusahaan' => $request->input('alamat_perusahaan'),
                'provinsi' => $request->input('provinsi'),
                'kota' => $request->input('kota'),
                'kecamatan' => $request->input('kecamatan'),
                'kelurahan' => $request->input('kelurahan'),
                'domisili' => storeFile($request, 'domisili', 'domisili', $namaPerusahaan),
                'domisili_due_date' => $request->input('domisili_due_date'),
                'email' => $request->input('email'),
                'telepon' => $request->input('telepon'),
                'fax' => $request->input('fax'),
                'nama_bank' => $request->input('nama_bank'),
                'alamat_bank' => $request->input('alamat_bank'),
                'no_rekening' => $request->input('no_rekening'),
                'nama_rekening' => $request->input('nama_rekening'),
                'mata_uang' => $request->input('mata_uang'),
                'alasan_rekening' => $request->input('alasan_rekening'),
                'form_bank' => storeFile($request, 'form_bank', 'form_bank', $namaPerusahaan),
                'nomor_npwp' => $request->input('nomor_npwp'),
                'copy_npwp' => storeFile($request, 'copy_npwp', 'npwp', $namaPerusahaan),
                'copy_spt' => storeFile($request, 'copy_spt', 'spt', $namaPerusahaan),
                'pertanyaan_nib' => $request->input('pertanyaan_nib'),
                'alasan_nib' => $request->input('alasan_nib'),
                'nib' => $request->input('nib'),
                'file_nib' => storeFile($request, 'file_nib', 'nib', $namaPerusahaan),
                'kbli' => $request->input('kbli'),
                'file_siup' => storeFile($request, 'file_siup', 'siup', $namaPerusahaan),
                'file_tdp' => storeFile($request, 'file_tdp', 'tdp', $namaPerusahaan),
                'pertanyaan_pajak' => $request->input('pertanyaan_pajak'),
                'file_sppkp' => storeFile($request, 'file_sppkp', 'sppkp', $namaPerusahaan),
                'file_non_pkp' => storeFile($request, 'file_non_pkp', 'non_pkp', $namaPerusahaan),
                'file_sertifikat' => storeFile($request, 'file_sertifikat', 'sertifikat', $namaPerusahaan),
                'file_iso_9001' => storeFile($request, 'file_iso_9001', 'iso', $namaPerusahaan),
                'file_iso_14001' => storeFile($request, 'file_iso_14001', 'iso', $namaPerusahaan),
                'file_iso_45001' => storeFile($request, 'file_iso_45001', 'iso', $namaPerusahaan),
                'file_svlk' => storeFile($request, 'file_svlk', 'svlk', $namaPerusahaan),
                'file_aeo' => storeFile($request, 'file_aeo', 'aeo', $namaPerusahaan),
                'vendor_accept' => $request->input('vendor_accept'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            DB::table('vendor_registrations')->insert($data);

            $response = array(
                'status' => true,
                'datas' => 'Berhasil Input Data'
            );
            return Response::json($response);

        } catch (QueryException $e) {
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                $response = array(
                    'status' => false,
                    'message' => 'Anda Sudah Mengisi Ini'
                );
                return Response::json($response);
            }
            else{
                $response = array(
                    'status' => false,
                    'message' => $e->getMessage()
                );
                return Response::json($response);
            }
        }
    }
}
