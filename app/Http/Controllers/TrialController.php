<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use Response;

class TrialController extends Controller
{
    public function testmail()
    {
        try {

            $bodyHtml = "MIS Test Mail";

            $to = [
                'rio.irvansyah@music.yamaha.com',
                'mokhamad.khamdan.khabibi@music.yamaha.com',
                'nasiqul.ibat@music.yamaha.com',
                'muhammad.ikhlas@music.yamaha.com'
            ];
            // Mail::html($bodyHtml, function ($message) use ($to, $bodyHtml) {
            //     $message->from('bridgeforvendor@ympi.co.id', 'BFV');
            //     $message->to($to);
            //     $message->subject('Trial');
            // });
            $data = 'Test';
            Mail::to($to)
            ->send(new SendEmail($data, 'test_mail'));
        } catch (\Exception$e) {
            echo $e->getMessage();
        }
    }

    public function trialPdf()
    {
        $depan = "QA Certificate - QA-CER-00013 (YMPI-QA-I-RCD001).pdf";
        $belakang = "QA Certificate Belakang - QA-CER-00013 (YMPI-QA-I-RCD001).pdf";
        $pdfFile1Path = public_path() . "/data_file/qa/certificate/" . $depan;
        $pdfFile2Path = public_path() . "/data_file/qa/certificate/" . $belakang;

        $merger = new Merger;
        $merger->addFile($pdfFile1Path);
        $merger->addFile($pdfFile2Path);

        $createdPdf = $merger->merge();

        $pathForTheMergedPdf = public_path() . "/data_file/qa/certificate_fix/QA-CER-00001.pdf";
        file_put_contents($pathForTheMergedPdf, $createdPdf);

        $fileNameFromDb = "trial_pdf";

        return response()->file($pathForTheMergedPdf, [
            'Content-Disposition' => 'inline; filename="' . $fileNameFromDb . '"',
        ]);
        // return new Response($createdPdf, 200, array('Content-Type' => 'application/pdf'));
    }
}
