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
        Schema::create('tblreviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tbluser_id')->constrained('tblusers')->onDelete('cascade');
            $table->foreignId('tblproduct_id')->constrained('tblproducts')->onDelete('cascade');
            $table->string('booking_id')->nullable();
            $table->integer('rating');
            $table->string('review')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblreviews');
    }
};
