<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$invitation = App\Models\Invitation::latest()->first();

if ($invitation) {
    $view = view('invitation.show', compact('invitation'))->render();
    echo $view;
} else {
    echo 'No invitation found.'.PHP_EOL;
}
