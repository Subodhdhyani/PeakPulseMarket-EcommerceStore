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
        Schema::create('tbltrackings', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            // As booking_id in tblbooking is not unique and non primary and string data type
            $table->string('booking_id')->unique(); // uniqueness for booking_id
            $table->foreign('booking_id')->references('booking_id')->on('tblbookings')->onDelete('cascade');
            $table->string('courier_name')->nullable();
            $table->string('courier_tracking_number')->nullable();
            $table->timestamp('order_status_0_to_1')->nullable(); // For storing date & time when order_status changes 
            $table->timestamp('order_status_1_to_2')->nullable();
            $table->timestamp('order_status_2_to_3')->nullable();
            $table->timestamp('order_status_0_to_4')->nullable();
            $table->timestamp('order_status_1_to_4')->nullable();
            $table->timestamp('order_status_4_to_5')->nullable();
            $table->timestamp('order_status_3_to_6')->nullable();
            $table->timestamp('order_status_6_to_4')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbltrackings');
    }
};
