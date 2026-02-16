<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $category = App\Models\Category::first();
    if (! $category) {
        $category = App\Models\Category::create(['name' => 'Test', 'slug' => 'test']);
    }

    echo 'Invitation Count before: '.App\Models\Invitation::count().PHP_EOL;

    $invitation = App\Models\Invitation::create([
        'category_id' => $category->id,
        'slug' => 'invitation-test-'.time(),
        'is_active' => true,
    ]);

    echo 'Created Invitation: '.$invitation->id.PHP_EOL;
    echo 'Invitation Count after: '.App\Models\Invitation::count().PHP_EOL;
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage().PHP_EOL;
}
