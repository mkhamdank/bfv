<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WhatsappController extends Controller
{
    public function __construct()
    {
        $phone = DB::table('all_controls')->where('note','whatsapp')->where('remark', 1)->first();
        $this->device = $phone->values;
    }
    public function whatspie($phone, $message)
    {

        $message = str_replace("%20", " ", $message);
        $message = str_replace("%0A", "\\n", $message);
        
        // if(php_sapi_name() === 'cli' || (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '10.109.33.34')){
        //     $mis_phone_number = [                            
        //         // '082244167224', #Mas Aga
        //         // '081554119011', #Mas Anton
        //         '082334197238', #Khamdan
        //         '085645896741', #Rio
        //         '08980198771',  #Ibat
        //         '082234955505', #Ikhlas
        //         '082111414954', #Hendra            
        //         '085155177297', #Thomi
        //     ];

        //     // append $phone to message then add newline
        //     $message = '*[TRIAL MIS BFV]*' . "\\n" . "(Received by MIS), real receiver is: " . $phone . "\\n" . $message;

        //     foreach ($mis_phone_number as $key => $value) {
        //         $mis_phone_number[$key] = (object) ['phone' => $value];
        //     }            

        //     foreach ($mis_phone_number as $key => $value) {
        //         if(substr($value->phone, 0, 1) == '+' ){
        //             $phone = substr($value->phone, 1, 15);
        //         }
        //         else if(substr($value->phone, 0, 1) == '0'){
        //             $phone = "62".substr($value->phone, 1, 15);
        //         }
        //         else{
        //             $phone = $value->phone;
        //         }                

        //         $curl = curl_init();

        //         curl_setopt_array($curl, array(
        //             CURLOPT_URL => 'https://api.whatspie.com/messages',
        //             CURLOPT_RETURNTRANSFER => true,
        //             CURLOPT_ENCODING => '',
        //             CURLOPT_MAXREDIRS => 10,
        //             CURLOPT_SSL_VERIFYHOST => false,
        //             CURLOPT_SSL_VERIFYPEER => false,
        //             CURLOPT_TIMEOUT => 0,
        //             CURLOPT_FOLLOWLOCATION => true,
        //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //             CURLOPT_CUSTOMREQUEST => 'POST',
        //             CURLOPT_POSTFIELDS => '{
        //             "device": "'.$this->device.'",
        //             "receiver": "' . $phone . '",
        //             "type": "chat",
        //             "message": "' . $message . '",
        //             "simulate_typing": 1
        //         }',
        //             CURLOPT_HTTPHEADER => array(
        //                 'Content-Type: application/json',
        //                 'Accept: application/json',
        //                 'Authorization: Bearer UAqINT9e23uRiQmYttEUiFQ9qRMUXk8sADK2EiVSgLODdyOhgU',
        //             ),
        //         ));
        //         curl_exec($curl);
        //     }

        // } else {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.whatspie.com/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "device": "'.$this->device.'",
                "receiver": "' . $phone . '",
                "type": "chat",
                "message": "' . $message . '",
                "simulate_typing": 1
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer UAqINT9e23uRiQmYttEUiFQ9qRMUXk8sADK2EiVSgLODdyOhgU',
                ),
            ));
            curl_exec($curl);

        // }        
    }

    public function whatspieImage($phone, $caption, $file_url)
    {

        $message = str_replace("%20", " ", $message);
        $message = str_replace("%0A", "\\n", $message);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.whatspie.com/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
          "device": "'.$this->device.'",
          "receiver": "' . $phone . '",
          "type": "image",
          "message": "' . $caption . '",
          "file_url": "' . $file_url . '",
          "simulate_typing": 1
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer UAqINT9e23uRiQmYttEUiFQ9qRMUXk8sADK2EiVSgLODdyOhgU',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}
