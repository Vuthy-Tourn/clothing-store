<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Barryvdh\DomPDF\Facade\Pdf;

class DomPDFServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Configure DomPDF default options
        Pdf::setOptions([
            'defaultFont' => 'khmer',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'fontDir' => storage_path('fonts/'),
            'fontCache' => storage_path('fonts/'),
            'tempDir' => storage_path('temp/'),
            'chroot' => realpath(base_path()),
            'logOutputFile' => storage_path('logs/dompdf.html'),
            'enableFontSubsetting' => true,
        ]);
    }
}