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
        Schema::create('tblorderhelps', function (Blueprint $table) {
            $table->id();

            $table->Integer('tbluser_id');
            // As booking_id in tblbooking is not unique and non primary and string data type
            $table->string('booking_id');
            $table->foreign('booking_id')->references('booking_id')->on('tblbookings')->onDelete('cascade');
            //$table->string('booking_id');//->index(); // Only adding an index

            $table->string('subject');
            $table->string('description');
            $table->enum("order_help_status", ['0', '1', '2'])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblorderhelps');
    }
};
