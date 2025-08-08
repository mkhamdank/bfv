<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class TestPuppeteerController extends Controller
{
    /**
     * Generates and downloads a PDF from a Blade view.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function generatePdfFromView()
    {
        // Increase PHP execution time to 5 minutes (300 seconds)
        // set_time_limit(300);

        // // Increase PHP memory limit to 256MB
        // ini_set('memory_limit', '256M');

        // // Sample dynamic data for the view
        // $data = [
        //     'title' => 'Puppeteer Test PDF',
        //     'currentTime' => now()->toDateTimeString(),
        //     'randomNumber' => rand(100, 999),
        // ];


        // // Render the Blade view to an HTML string
        // $html = View::make('test-puppeteer', $data)->render();

        // try {
        // // Use Browsershot to convert the HTML to a PDF
        // $filePath = storage_path('app/test-puppeteer-output.pdf');

        // $pdf = Browsershot::html($html)
        //     ->format('A4')
        //     ->margins(20, 20, 20, 20)
        //     ->noSandbox()
        //     ->timeout(60000)
        //     ->setChromePath('C:/Users/YMPI/.cache/puppeteer/chrome/win64-139.0.7258.66/chrome-win64/chrome.exe')
        //     ->waitUntilNetworkIdle() // waits for requests to finish
        //     ->save($filePath);

        // if (file_exists($filePath)) {
        //     return response()->download($filePath, 'test-puppeteer-output.pdf');
        // } else {
        //     return 'PDF file was not created. Check your storage/app directory permissions.';
        // }

        // } catch (\Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot $e) {
        //     // This is where you'll find the detailed error from Browsershot/Puppeteer.
        //     // It's crucial to examine this message.
        //     dd('Browsershot Error:', $e->getMessage(), 'Full command:', $e->getCommand());
        // }
        
        // 1. Prepare the data for the view
        $data = [
            'title' => 'Puppeteer Test PDF',
            'currentTime' => now()->toDateTimeString(),
            'randomNumber' => rand(100, 999),
        ];

        // 2. Render the Blade view to an HTML string
        $html = View::make('test-puppeteer', $data)->render();

        // 3. Define the settings for Browsershot
        // This is an optional step, but it allows for easy customization.
        $settings = [
            'format' => 'A4',
            'margins' => [20, 20, 20, 20],
            'timeout' => 60,
            // 'setNodeBinary' => 'C:\Program Files\nodejs\node.exe', // Add if needed
            // 'setChromePath' => 'C:\Program Files\Google\Chrome\Application\chrome.exe', // Add if needed
        ];

        // 4. Call the helper function to generate and download the PDF
        // The helper function handles saving, downloading, and cleanup.
        return generatePdfFromHtml($html, $settings, 'test-puppeteer-output.pdf');
    }
}