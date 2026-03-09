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
        Schema::create('wedding_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('date_text')->nullable();
            $table->string('location_button_text')->nullable();
            
            $table->string('hero_image')->nullable();
            $table->string('story_image')->nullable();
            $table->json('gallery_images')->nullable();
            
            $table->string('story_title')->nullable();
            $table->text('story_text')->nullable();
            $table->string('story_groom_name')->nullable();
            $table->text('story_groom_text')->nullable();
            $table->string('story_bride_name')->nullable();
            $table->text('story_bride_text')->nullable();
            
            $table->text('quote_text')->nullable();
            $table->string('quote_author')->nullable();
            
            $table->dateTime('event_date')->nullable();
            
            $table->string('ceremony_title')->nullable();
            $table->string('ceremony_time')->nullable();
            $table->string('ceremony_location')->nullable();
            
            $table->string('reception_title')->nullable();
            $table->string('reception_time')->nullable();
            $table->string('reception_location')->nullable();
            
            $table->string('theme')->default('Lino');
            $table->string('primary_color')->default('#8B8B8B');
            $table->string('secondary_color')->default('#E5E0D8');
            
            $table->text('map_url')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_templates');
    }
};
