<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

test('can create invitation with file', function () {
    Storage::fake('public_uploads');

    Role::create(['name' => 'admin']);
    $user = User::factory()->create();
    $user->assignRole('admin');

    $category = Category::create(['name' => 'Test', 'slug' => 'test']);

    $file = UploadedFile::fake()->image('cover.jpg');

    livewire(App\Filament\Resources\InvitationResource\Pages\CreateInvitation::class)
        ->fillForm([
            'category_id' => $category->id,
            'slug' => 'my-invitation',
            'cover_photo_path' => $file,
            'is_active' => true,
            'rsvp_method' => 'whatsapp',
        ])
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('invitations', [
        'slug' => 'my-invitation',
        'category_id' => $category->id,
    ]);
});
