<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$invitation = App\Models\Invitation::latest()->first();

if ($invitation) {
    $view = view('invitation.show', compact('invitation'))->render();
    // Extract the background image URL
    preg_match('/background-image: url\(\'(.*?)\'\)/', $view, $matches);
    if (isset($matches[1])) {
        echo 'Background Image URL: '.$matches[1].PHP_EOL;
    } else {
        echo 'Background Image URL not found in view.'.PHP_EOL;
    }
} else {
    echo 'No invitation found.'.PHP_EOL;
}
