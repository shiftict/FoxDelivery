<?php

return [
    'mode'                  => 'utf-8',
    'format'                => 'A4',
    'author'                => '',
    'subject'               => '',
    'keywords'              => '',
    'creator'               => 'Laravel Pdf',
    'display_mode'          => 'fullpage',
    'tempDir'               => public_path('temp'),
    'font_path'             => base_path('public/panel/assets/fonts/'),
    'pdf_a'                 => false,
    'pdf_a_auto'            => false,
    'icc_profile_path'      => '',
    'font_data' => [
        'droidregular' => [
            'R'  => 'almarai_regular.ttf',    // regular font
            'B'  => 'almarai_regular.ttf',       // optional: bold font
            'I'  => 'almarai_regular.ttf',     // optional: italic font
            'BI' => 'almarai_regular.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ]
    ]
];
