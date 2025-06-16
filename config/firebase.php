<?php

return [
    'credentials' => [
        'file' => storage_path('app/firebase-credentials.json'),
    ],
    'database_url' => env('FIREBASE_DATABASE_URL'),
];
