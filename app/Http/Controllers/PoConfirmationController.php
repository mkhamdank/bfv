<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

use Response;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

class PoConfirmationController extends Controller
{

    public function indexPoConfirmation(Request $request)
    {

        return view('404');

        // try {
        //     $po = $request->get('po');

        //     return view('raw_material.po_confirmation',
        //         array(
        //             'check_po' => $po,
        //         )
        //     );
        // } catch (\Exception$e) {
        //     return view('404');
        // }
    }


    public function indexPoConfirmationEquipment(Request $request)
    {
        try{
            $po = $request->get('po');


            $data = db::table('equipment_plan_deliveries')
            ->where('equipment_plan_deliveries.no_po', $po)
            ->where('equipment_plan_deliveries.po_confirm', 0)
            ->get();

            if (count($data) > 0) {
                return view('po_confirmation.index_po_eq_confirmation',
                    array(
                        'check_po' => $po,
                    )
                );
            }else{
                return view('po_confirmation.confirmed_po_eq',
                    array(
                        'check_po' => $po,
                    )
                );
            }

            
        } catch (\Exception $e){
            return view('404');
        }
    }

    public function fetchPoEquipment(Request $request)
    {
        try{
            $po_number = $request->get('po_number');

            $data = db::table('equipment_plan_deliveries')
            ->where('equipment_plan_deliveries.no_po', $po_number)
            ->where('equipment_plan_deliveries.po_confirm', 0)
            ->get();

            $drivers = [];
            $file_txt_driver_name = file_exists(public_path('driver/driver_name.txt')) ? array_filter(array_map('trim', explode("\n", file_get_contents(public_path('driver/driver_name.txt'))))) : null;
            $file_txt_driver_plat_no = file_exists(public_path('driver/driver_plat_no.txt')) ? array_filter(array_map('trim', explode("\n", file_get_contents(public_path('driver/driver_plat_no.txt'))))) : null;
            $file_txt_driver_car = file_exists(public_path('driver/driver_car.txt')) ? array_filter(array_map('trim', explode("\n", file_get_contents(public_path('driver/driver_car.txt'))))) : null;
            $file_txt_driver_phone = file_exists(public_path('driver/driver_phone.txt')) ? array_filter(array_map('trim', explode("\n", file_get_contents(public_path('driver/driver_phone.txt'))))) : null;
            if(count($data) > 0){
                for ($i = 0; $i < count($data); $i++) {
                    if($data[$i]->driver){
                        $drivers[] = $data[$i]->driver;
                    }
                }
            }

            $response = array(
                'status' => true,
                'data' => $data,
                'drivers' => $drivers,
                'file_txt_driver_name' => $file_txt_driver_name,
                'file_txt_driver_plat_no' => $file_txt_driver_plat_no,
                'file_txt_driver_car' => $file_txt_driver_car,
                'file_txt_driver_phone' => $file_txt_driver_phone,
            );
            return Response::json($response);
        } catch (\Exception $e){
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);
        }
    }
    public function inputPoConfirmationEquipment(Request $request)
    {
        $po_number = $request->get('po_number');
        $data = $request->get('data');
        $now = date('Y-m-d H:i:s');

        $po = db::table('equipment_plan_deliveries')
        ->where('equipment_plan_deliveries.no_po', $po_number)
        ->get();

        DB::beginTransaction();

        try {
            $driver_data = [];
            for ($i = 0; $i < count($po); $i++) {
                for ($j = 0; $j < count($data); $j++) {
                    if ($po[$i]->no_item == $data[$j]['no_item']) {
                        $driver_name = null;
                        $driver_plat_no = null;
                        $driver_car = null;
                        $driver_phone = null;
                        if(isset($data[$j]['driver']) && $data[$j]['driver'] != null && count($data[$j]['driver']) > 0){
                            $driver_detail = $data[$j]['driver'][0]['driver_detail'];
                            $driver_name = $data[$j]['driver'][0]['driver_name'];
                            $driver_plat_no = $data[$j]['driver'][0]['driver_plat_no'];
                            $driver_car = $data[$j]['driver'][0]['driver_car'];
                            $driver_phone = $data[$j]['driver'][0]['driver_phone'];
                            array_push($driver_data, [
                                'driver_detail' => $driver_detail,
                                'driver_name' => $driver_name,
                                'driver_plat_no' => $driver_plat_no,
                                'driver_car' => $driver_car,
                                'driver_phone' => $driver_phone,
                            ]);
                        }
                        $update = db::table('equipment_plan_deliveries')
                        ->where('equipment_plan_deliveries.no_po', $po_number)
                        ->where('equipment_plan_deliveries.no_item', $data[$j]['no_item'])
                        ->update([
                            'need_if' => 1,
                            'po_confirm' => 1,
                            'po_confirm_at' => $now,
                            'driver_name' => $driver_name,
                            'driver_plat_no' => $driver_plat_no,
                            'driver_car' => $driver_car,
                            'driver_phone' => $driver_phone,
                            'note' => $data[$j]['note'],
                        ]);

                        break;
                    }
                }
            }


            // Save driver_data to XML file
            if(count($driver_data) > 0) {
                $this->saveDriverDataToTxt($po_number, $driver_data);

                $mail_to = [];
                $bcc = [];
                array_push($mail_to, 'ali.murdani@music.yamaha.com');
                array_push($mail_to, 'widura@music.yamaha.com');
                array_push($bcc, 'ympi-mis-ML@music.yamaha.com');

                $bodyHtml2 = "<html><body style='font-family: Arial, sans-serif; color: #333;'>";
                $bodyHtml2 .= "<div style='max-width: 600px; margin: 0 auto; padding: 20px;'>";
                $bodyHtml2 .= "<h2 style='color: #1a73e8; border-bottom: 2px solid #1a73e8; padding-bottom: 10px;'>PO Confirmation - Driver Details</h2>";
                $bodyHtml2 .= "<p style='margin-top: 20px;'><strong>PO Number:</strong> " . htmlspecialchars($po_number) . "</p>";
                $bodyHtml2 .= "<table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>";
                $bodyHtml2 .= "<thead>";
                $bodyHtml2 .= "<tr style='background-color: #f2f2f2; border-bottom: 2px solid #ddd;'>";
                $bodyHtml2 .= "<th style='text-align: left; padding: 12px; border: 1px solid #ddd;'>Surat Tugas</th>";
                $bodyHtml2 .= "<th style='text-align: left; padding: 12px; border: 1px solid #ddd;'>Driver Name</th>";
                $bodyHtml2 .= "<th style='text-align: left; padding: 12px; border: 1px solid #ddd;'>Plate Number</th>";
                $bodyHtml2 .= "<th style='text-align: left; padding: 12px; border: 1px solid #ddd;'>Car Type</th>";
                $bodyHtml2 .= "<th style='text-align: left; padding: 12px; border: 1px solid #ddd;'>Phone</th>";
                $bodyHtml2 .= "</tr>";
                $bodyHtml2 .= "</thead>";
                $bodyHtml2 .= "<tbody>";
                
                if (is_array($driver_data) && count($driver_data) > 0) {
                    foreach ($driver_data as $driver) {
                        $bodyHtml2 .= "<tr>";
                        $bodyHtml2 .= "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($driver['driver_detail'] ?? '-') . "</td>";
                        $bodyHtml2 .= "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($driver['driver_name'] ?? '-') . "</td>";
                        $bodyHtml2 .= "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($driver['driver_plat_no'] ?? '-') . "</td>";
                        $bodyHtml2 .= "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($driver['driver_car'] ?? '-') . "</td>";
                        $bodyHtml2 .= "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($driver['driver_phone'] ?? '-') . "</td>";
                        $bodyHtml2 .= "</tr>";
                    }
                }
                
                $bodyHtml2 .= "</tbody>";
                $bodyHtml2 .= "</table>";
                $bodyHtml2 .= "<div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;'>";
                $bodyHtml2 .= "<p style='color: red; font-size: 12px; font-weight: bold;'>Data will be synced in the next hour at the 20-minute mark.</p>";
                $bodyHtml2 .= "<p>This is an automated email from PT. Yamaha Musical Products Indonesia MIS System.</p>";
                $bodyHtml2 .= "<p>Please do not reply to this email.</p>";
                $bodyHtml2 .= "</div>";
                $bodyHtml2 .= "</div>";
                $bodyHtml2 .= "</body></html>";
                Mail::send([], [], function ($message) use ($mail_to, $bcc, $po_number, $bodyHtml2) {
                    $message->from('bridgeforvendor@ympi.co.id', 'PT. Yamaha Musical Products Indonesia');
                    $message->to($mail_to);
                    $message->bcc($bcc);
                    $message->subject('[PO CONFIRMATION] : ' . $po_number);
                    $message->html($bodyHtml2);
                });
            }

            // $notification = $this->sendPoNotificationEquipment($po_number);

            DB::commit();

            die();
            $response = array(
                'status' => true,
            );
            return Response::json($response);

        } catch (Exception $e) {
            DB::rollback();

            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);
        }

    }
    

    function saveDriverDataToTxt($po_number, $driver_data) {
        $fields = ['driver_name', 'driver_plat_no', 'driver_car', 'driver_phone'];
        
        foreach ($fields as $field) {
            $file_path = public_path('driver/' . $field . '.txt');
            $existing_entries = [];
            
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                $existing_entries = array_filter(array_map('trim', explode(PHP_EOL, $content)));
            }
            
            if (is_array($driver_data)) {
                foreach ($driver_data as $driver) {
                    if (isset($driver[$field]) && !empty($driver[$field])) {
                        $entry = $driver[$field];
                        if (!in_array($entry, $existing_entries)) {
                            $existing_entries[] = $entry;
                        }
                    }
                }
            }
            
            if (!empty($existing_entries)) {
                $file_content = implode(PHP_EOL, $existing_entries);
                $file_content .= PHP_EOL;
                file_put_contents($file_path, $file_content);
            }
        }
    }

    // public function sendPoNotificationEquipment($po_number)
    // {

    //     $exclude = [
    //         'Y10053',
    //         'Y31520',
    //         'Y81811',

    //         'Y31504',
    //         'Y10022',
    //         'Y81801',
    //     ];

    //     $bcc = [
    //         'rio.irvansyah@music.yamaha.com',
    //     ];

    //     $delivery = db::table('material_plan_deliveries')
    //         ->where('po_number', $po_number)
    //         ->first();

    //     $notes = db::table('material_plan_deliveries')
    //         ->leftJoin('material_controls', 'material_controls.material_number', '=', 'material_plan_deliveries.material_number')
    //         ->where('po_number', $po_number)
    //         ->whereNotNull('note')
    //         ->select(
    //             'material_plan_deliveries.*',
    //             'material_controls.material_description'
    //         )
    //         ->get();

    //     $material = db::table('material_plan_deliveries')
    //         ->leftJoin('material_controls', 'material_controls.material_number', '=', 'material_plan_deliveries.material_number')
    //         ->leftJoin('users AS buyer_proc', 'buyer_proc.username', '=', 'material_controls.pic')
    //         ->leftJoin('users AS control_proc', 'control_proc.username', '=', 'material_controls.control')
    //         ->where('po_number', $po_number)
    //         ->select(
    //             'material_controls.vendor_code',
    //             'material_controls.vendor_name',
    //             db::raw('buyer_proc.email AS buyer_email'),
    //             db::raw('control_proc.email AS control_email')
    //         )
    //         ->first();

    //     $attention = '';
    //     $to = [];
    //     $cc = [
    //         $material->buyer_email,
    //         $material->control_email,
    //     ];

    //     $vendor_mails = db::table('vendor_mails')
    //         ->where('vendor_code', $material->vendor_code)
    //         ->get();

    //     for ($j = 0; $j < count($vendor_mails); $j++) {
    //         if ($vendor_mails[$j]->remark == 'to') {
    //             $attention = $vendor_mails[$j]->name;
    //             $to[] = $vendor_mails[$j]->email;
    //         } else {
    //             $cc[] = $vendor_mails[$j]->email;
    //         }
    //     }

    //     // If Yamaha Group
    //     if (in_array($material->vendor_code, $exclude)) {
    //         $to = [
    //             $material->buyer_email,
    //             $material->control_email,
    //         ];
    //         $cc = [];
    //     }

    //     $data = [
    //         'buyer' => $material->buyer_email,
    //         'control' => $material->control_email,
    //         'po_number' => $po_number,
    //         'vendor_code' => $material->vendor_code,
    //         'vendor_name' => $material->vendor_name,
    //         'subject' => 'PO CONFIRMATION REPORT : ' . $po_number . '_' . $material->vendor_name,
    //         'delivery' => $delivery,
    //         'notes' => $notes,
    //     ];

    //     try {
    //         Mail::to($to)
    //             ->cc($cc)
    //             ->bcc($bcc)
    //             ->send(new SendEmail($data, 'send_po_notification'));

    //     } catch (Exception $e) {

    //         $insert = DB::table('error_logs')
    //             ->insert([
    //                 'error_message' => '[PO] : ' . $e->getMessage(),
    //                 'created_by' => 1,
    //                 'created_at' => date('Y-m-d H:i:s'),
    //                 'updated_at' => date('Y-m-d H:i:s'),
    //             ]);

    //     }
    // }




    public function indexVendorConfirmation(Request $request)
    {
        try{
            $code = $request->get('code');

            $data = db::table('vendor_gifts')
            ->where('vendor_gifts.unique_code', $code)
            ->first();

            if ($data) {
                if($data->confirmation == null){
                    $update_data = db::table('vendor_gifts')
                    ->where('vendor_gifts.unique_code', $code)
                    ->update([
                        'confirmation' => 'Confirmed',
                        'confirmed_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                return view('vendor.vendor_confirmation',
                    array(
                        'data' => $data,
                    )
                );
            }else{
                return view('vendor.confirmed_po_eq',
                    array(
                        'data' => $data,
                    )
                );
            }

            
        } catch (\Exception $e){
            return view('404');
        }
    }

    public function indexVendorHoliday(Request $request)
    {
        try{
            $code = $request->get('code');

            $data = db::table('vendor_gifts')
            ->where('vendor_gifts.unique_code', $code)
            ->first();

            if ($data) {
                if($data->confirmation_holiday == null){
                    $update_data = db::table('vendor_gifts')
                    ->where('vendor_gifts.unique_code', $code)
                    ->update([
                        'confirmation_holiday' => 'Confirmed',
                        'confirmed_at_holiday' => date('Y-m-d H:i:s'),
                    ]);
                }
                return view('vendor.vendor_holiday',
                    array(
                        'data' => $data,
                    )
                );
            }else{
                return view('vendor.confirmed_po_eq',
                    array(
                        'data' => $data,
                    )
                );
            }

            
        } catch (\Exception $e){
            return view('404');
        }
    }


}
