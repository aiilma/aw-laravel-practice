<?php

return [
    'uploader' => [
        'max_file_size_mb' => env('ACCOUNT_UPLOADER_MAX_FILE_SIZE_MB', 150),
    ],

    'products_limit' => env('ACCOUNT_PRODUCTS_LIMIT', 4),
];