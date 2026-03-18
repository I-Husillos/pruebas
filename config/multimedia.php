<?php

return [
    'max_image_size' => 1024 * 5, // 5MB
    'max_video_size' => 1024 * 50, // 50MB
    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    'allowed_video_types' => ['video/mp4', 'video/webm', 'video/ogg'],

'root_path' => 'uploads', 
    'folder_names' => [
        'images' => 'images',
        'videos' => 'videos',
    ],
    'default_market' => 'global',
];
