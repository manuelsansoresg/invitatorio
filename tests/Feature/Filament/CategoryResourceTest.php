<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

test('can create category', function () {
    Role::create(['name' => 'admin']);
    $user = User::factory()->create();
    $user->assignRole('admin');

    livewire(App\Filament\Resources\CategoryResource\Pages\CreateCategory::class)
        ->fillForm([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ])
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('categories', [
        'name' => 'Test Category',
        'slug' => 'test-category',
    ]);
});
