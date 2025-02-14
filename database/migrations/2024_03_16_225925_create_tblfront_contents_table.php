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
        Schema::create('tblfront_contents', function (Blueprint $table) {
            $table->id();
            $table->string('firstpic')->nullable();
            $table->string('secondpic')->nullable();
            $table->string('thirdpic')->nullable();
            $table->string('email')->nullable();
            $table->string('number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblfront_contents');
    }
};
