<?php

return [
    'defaultFont' => 'khmer',
    'fontDir' => storage_path('fonts'),
    'fontCache' => storage_path('fonts'),
    'tempDir' => storage_path('temp'),
    'chroot' => realpath(base_path()),
    'isRemoteEnabled' => false,
    'isHtml5ParserEnabled' => true,
    'isPhpEnabled' => false,
    'isFontSubsettingEnabled' => true,
    'defaultPaperSize' => 'A4',
    'defaultPaperOrientation' => 'portrait',
    'dpi' => 96,
];