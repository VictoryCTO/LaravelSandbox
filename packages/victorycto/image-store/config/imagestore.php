<?php

return [
    'table_name' => 'image_store',
    'options' => [
        'full' => [
            'size' => null, //means original size
            'quality' => 100,
            'model_attribute' => 'full_url',
        ],
        'small' => [
            'size' => 600,
            'quality' => 80,
            'model_attribute' => 'small_url',
        ],
        'thumbnail' => [
            'size' => 300,
            'quality' => 70,
            'model_attribute' => 'thumbnail_url',
        ],
    ],
];
