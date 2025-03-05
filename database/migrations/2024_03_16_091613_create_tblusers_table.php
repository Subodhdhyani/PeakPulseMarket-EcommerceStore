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
        Schema::create('tblusers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->default('user');
            //$table->enum('role', ['admin', 'user'])->default('user');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('profile_pic')->nullable();
            $table->string('address')->nullable();
            $table->string('password');
            $table->enum('status', ['block', 'unblock'])->default('unblock');
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblusers');
    }
};
