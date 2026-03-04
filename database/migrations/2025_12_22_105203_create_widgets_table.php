<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('widget_zones')->onDelete('cascade');
            $table->enum('type', ['menu', 'form', 'wysiwyg', 'fixed_content']);
            $table->json('title')->nullable(); // {es: string, en: string}
            $table->json('config'); // Type-specific configuration
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->json('visibility_rules')->nullable(); // Show/hide conditions
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('widgets');
    }
};
