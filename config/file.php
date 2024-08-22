<?php

return [
    // This array defines the file extensions that are allowed for image uploads.
    // Only files with these extensions will be accepted as valid image uploads.
    'allowed_images' => ['png', 'jpeg', 'jpg', 'webp', 'gif'],

    // This array defines the file extensions that are allowed for video uploads.
    // Only files with these extensions will be accepted as valid video uploads.
    'allowed_videos' => ['mp4', 'avi'],

    // This specifies the name of the folder where temporary files will be stored.
    // Temporary files are typically used for holding uploads before they are processed
    // or moved to their final location.
    'temporary_folder' => 'temporary',

];
