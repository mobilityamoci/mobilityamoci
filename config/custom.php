<?php
return [
    'geo' => [
        'piacenza_istat' => 33032
    ],
    'lizmap' => [
        'host' => env('LIZMAP_HOST'),
        'provinciale' => env('LIZMAP_PROVINCIALE'),
        'scolastico' => env('LIZMAP_SCOLASTICO'),
    ],
    'lang' => [
        'school' => env('SCHOOL_NAME'),
        'section' => env('SECTION_NAME'),
        'student' => env('STUDENT_NAME')
    ],
    'nominatim' => [
        'url' => env('NOMINATIM_ENDPOINT'),
    ]
];
