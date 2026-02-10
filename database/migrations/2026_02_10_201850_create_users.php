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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('username',50)->unique();
            $table->string('email',50)->unique()->nullable();
            $table->string('password');
            $table->text('bio')->nullable();
            $table->string('avatar_path',50)->nullable();
            $table->string('github',50)->nullable();
            $table->string('linkedin',50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
