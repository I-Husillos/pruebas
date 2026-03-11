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
        Schema::create('change_controls', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('changeable');
            $table->json('payload')->nullable();
            $table->string('type')->default('update');
            $table->string('title');
            $table->text('description');
            $table->string('status')->default('draft');
            $table->foreignId('requester_id')->constrained('users');
            $table->text('reason')->nullable();
            $table->text('impact')->nullable();
            $table->timestamp('approval_date')->nullable();
            $table->timestamp('implementation_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_controls');
    }
};
