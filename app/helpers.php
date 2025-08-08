<?php

use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

if (!function_exists('generatePdfFromHtml')) {
    /**
     * Generates a PDF from an HTML string and streams it for download.
     *
     * @param string $html The HTML content to convert.
     * @param array $settings An associative array of Browsershot settings.
     * @param string $filename The name of the file for the user to download.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function generatePdfFromHtml(string $html, array $settings = [], string $filename = 'document.pdf')
    {
        $tempDir = storage_path('app/temp');
        if (!File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $filePath = $tempDir . '/' . uniqid() . '.pdf';

        // Set default Browsershot options
        $browsershot = Browsershot::html($html);

        // // Pass the path from the .env file to the Browsershot process
        // if (env('BROWSERSHOT_PATH')) {
        //     $browsershot->setEnv(['PATH' => env('BROWSERSHOT_PATH') . ':' . getenv('PATH')]);
        // }

        // Apply any custom settings from the input array
        if (!empty($settings)) {
            foreach ($settings as $method => $args) {
                if (is_array($args)) {
                    $browsershot->$method(...$args);
                } else {
                    $browsershot->$method($args);
                }
            }
        }
        
        $browsershot->setChromePath(env('PUPPETEER_CHROME_PATH'));
        
        $browsershot->save($filePath);

        $response = Response::download($filePath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);

        // Use the terminating callback to ensure the temporary file is deleted
        app()->terminating(function () use ($filePath) {
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        });

        return $response;
    }
}