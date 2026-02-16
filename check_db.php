<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo 'Count before: '.App\Models\Category::count().PHP_EOL;
    $category = App\Models\Category::create(['name' => 'Test Category', 'slug' => 'test-category-'.time()]);
    echo 'Created: '.$category->id.PHP_EOL;
    echo 'Count after: '.App\Models\Category::count().PHP_EOL;
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage().PHP_EOL;
}
