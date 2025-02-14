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
        Schema::create('tblcarts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tbluser_id')->constrained('tblusers')->onDelete('cascade');
            $table->foreignId('tblproduct_id')->constrained('tblproducts')->onDelete('cascade');

            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblcarts');
    }
};
