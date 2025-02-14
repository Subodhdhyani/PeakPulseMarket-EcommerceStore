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
        Schema::create('tblreturntrackings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->unique(); //uniqueness for booking_id
            $table->foreign('booking_id')->references('booking_id')->on('tblbookings')->onDelete('cascade');
            $table->string('courier_name')->nullable();
            $table->string('courier_tracking_number')->nullable();
            $table->enum("return_status", ['0', '1'])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblreturntrackings');
    }
};
