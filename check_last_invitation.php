<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$invitation = App\Models\Invitation::latest()->first();

if ($invitation) {
    echo 'ID: '.$invitation->id.PHP_EOL;
    echo 'Slug: '.$invitation->slug.PHP_EOL;
    echo 'Cover Photo Path: '.$invitation->cover_photo_path.PHP_EOL;
    echo 'Background Music Path: '.$invitation->background_music_path.PHP_EOL;
} else {
    echo 'No invitation found.'.PHP_EOL;
}
