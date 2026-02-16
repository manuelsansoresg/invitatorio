<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();

            // Estética
            $table->string('cover_photo_path')->nullable();
            $table->string('background_music_path')->nullable();

            // Evento
            $table->dateTime('event_date')->nullable();
            $table->text('dedication')->nullable();

            // Ubicaciones - Ceremonia
            $table->string('ceremony_location_name')->nullable();
            $table->string('ceremony_address')->nullable();
            $table->time('ceremony_time')->nullable();
            $table->string('ceremony_map_link')->nullable();

            // Ubicaciones - Recepción
            $table->string('reception_location_name')->nullable();
            $table->string('reception_address')->nullable();
            $table->time('reception_time')->nullable();
            $table->string('reception_map_link')->nullable();

            // Detalles
            $table->string('dress_code')->nullable();
            $table->string('gift_table_link')->nullable();
            $table->string('instagram_hashtags')->nullable();

            // Interacción
            $table->boolean('is_active')->default(true);
            $table->string('rsvp_method')->default('whatsapp');
            $table->string('whatsapp_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
