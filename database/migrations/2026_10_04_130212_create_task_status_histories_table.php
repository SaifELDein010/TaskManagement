<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::create('task_status_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();

            $table->string('new_status');

            $table->foreignId('changed_by')->constrained('users')->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_status_histories');
    }
};