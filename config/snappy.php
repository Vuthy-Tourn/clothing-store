<?php
return [
'pdf' => [
    'enabled' => true,
    'binary' => env('WKHTMLTOPDF_BINARY', '/usr/local/bin/wkhtmltopdf'),
    'options' => [
        'encoding' => 'UTF-8',
        'enable-local-file-access' => true,
    ],
],
]
?>