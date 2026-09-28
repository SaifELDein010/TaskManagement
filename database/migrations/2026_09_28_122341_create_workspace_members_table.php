<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{

    public function up(): void {
        Schema::create('workspace_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces');
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('is_owner');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('workspace_members');
    }
};
