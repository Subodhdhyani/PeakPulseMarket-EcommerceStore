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
        Schema::create('tblbookings', function (Blueprint $table) {
            $table->id();
            //tbluser  and tblproduct foreign key here for user and order related details
            $table->foreignId('tbluser_id')->constrained('tblusers')->onDelete('cascade');
            $table->foreignId('tblproduct_id')->constrained('tblproducts')->onDelete('cascade');

            //these required in razorpay for payment identification
            $table->string('order_id')->nullable(); //->unique();   //by razorpay server
            $table->string('payment_id')->nullable(); //->unique();  //by razorpay server
            $table->string('booking_id')->index(); //by locally our random code  

            //Here all these things required because for particular order this need as user change their details but they remain same for particular order 
            $table->string('billing_name');
            $table->string('billing_email');
            $table->string('billing_phone');
            $table->string('billing_address');

            //Because the prices changes so its required here to store at that time price
            $table->bigInteger('sale_prices');
            $table->bigInteger('delivery_charges');
            $table->Integer('total_order_quantity');
            $table->bigInteger('total_amount_paid');
            $table->string('payment_mode')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('currency')->nullable();
            $table->enum("order_status", ['0', '1', '2', '3', '4', '5', '6'])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblbookings');
    }
};
