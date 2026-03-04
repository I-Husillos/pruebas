<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('widget_zones', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'header', 'footer'
            $table->json('name'); // {es: string, en: string}
            $table->json('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('widget_zones');
    }
};
