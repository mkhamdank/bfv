<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

            return view('po_confirmation.index_equipment',
                array(
                    'check_po' => $po,
                )
            );
        } catch (\Exception $e){
            return view('404');
        }
    }

    public function fetchPoEquipment(Request $request)
    {
        try{
            $po_number = $request->get('po_number');

            $data = db::table('material_plan_deliveries')
                ->leftJoin('material_controls', 'material_controls.material_number', '=', 'material_plan_deliveries.material_number')
                ->where('material_plan_deliveries.po_number', $po_number)
                ->where('material_plan_deliveries.po_confirm', 0)
                ->orderBy('item_line', 'ASC')
                ->get();

            $response = array(
                'status' => true,
                'data' => $data,
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

        $po = db::table('material_plan_deliveries')
            ->where('material_plan_deliveries.po_number', $po_number)
            ->orderBy('item_line', 'ASC')
            ->get();

        DB::beginTransaction();

        try {
            for ($i = 0; $i < count($po); $i++) {
                for ($j = 0; $j < count($data); $j++) {
                    if ($po[$i]->item_line == $data[$j]['item_line']) {
                        $update = db::table('material_plan_deliveries')
                            ->where('material_plan_deliveries.po_number', $po_number)
                            ->where('material_plan_deliveries.item_line', $data[$j]['item_line'])
                            ->update([
                                'need_if' => 1,
                                'po_confirm' => 1,
                                'po_confirm_at' => $now,
                                'note' => $data[$j]['note'],
                            ]);

                        break;
                    }
                }
            }

            $notification = $this->sendPoNotification($po_number);

            DB::commit();
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

    public function sendPoNotificationEquipment($po_number)
    {

        $exclude = [
            'Y10053',
            'Y31520',
            'Y81811',

            'Y31504',
            'Y10022',
            'Y81801',
        ];

        $bcc = [
            'muhammad.ikhlas@music.yamaha.com',
        ];

        $delivery = db::table('material_plan_deliveries')
            ->where('po_number', $po_number)
            ->first();

        $notes = db::table('material_plan_deliveries')
            ->leftJoin('material_controls', 'material_controls.material_number', '=', 'material_plan_deliveries.material_number')
            ->where('po_number', $po_number)
            ->whereNotNull('note')
            ->select(
                'material_plan_deliveries.*',
                'material_controls.material_description'
            )
            ->get();

        $material = db::table('material_plan_deliveries')
            ->leftJoin('material_controls', 'material_controls.material_number', '=', 'material_plan_deliveries.material_number')
            ->leftJoin('users AS buyer_proc', 'buyer_proc.username', '=', 'material_controls.pic')
            ->leftJoin('users AS control_proc', 'control_proc.username', '=', 'material_controls.control')
            ->where('po_number', $po_number)
            ->select(
                'material_controls.vendor_code',
                'material_controls.vendor_name',
                db::raw('buyer_proc.email AS buyer_email'),
                db::raw('control_proc.email AS control_email')
            )
            ->first();

        $attention = '';
        $to = [];
        $cc = [
            $material->buyer_email,
            $material->control_email,
        ];

        $vendor_mails = db::table('vendor_mails')
            ->where('vendor_code', $material->vendor_code)
            ->get();

        for ($j = 0; $j < count($vendor_mails); $j++) {
            if ($vendor_mails[$j]->remark == 'to') {
                $attention = $vendor_mails[$j]->name;
                $to[] = $vendor_mails[$j]->email;
            } else {
                $cc[] = $vendor_mails[$j]->email;
            }
        }

        // If Yamaha Group
        if (in_array($material->vendor_code, $exclude)) {
            $to = [
                $material->buyer_email,
                $material->control_email,
            ];
            $cc = [];
        }

        $data = [
            'buyer' => $material->buyer_email,
            'control' => $material->control_email,
            'po_number' => $po_number,
            'vendor_code' => $material->vendor_code,
            'vendor_name' => $material->vendor_name,
            'subject' => 'PO CONFIRMATION REPORT : ' . $po_number . '_' . $material->vendor_name,
            'delivery' => $delivery,
            'notes' => $notes,
        ];

        try {
            Mail::to($to)
                ->cc($cc)
                ->bcc($bcc)
                ->send(new SendEmail($data, 'send_po_notification'));

        } catch (Exception $e) {

            $insert = DB::table('error_logs')
                ->insert([
                    'error_message' => '[PO] : ' . $e->getMessage(),
                    'created_by' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

        }
    }


}
