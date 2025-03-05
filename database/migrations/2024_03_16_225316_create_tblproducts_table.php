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
        Schema::create('tblproducts', function (Blueprint $table) {
            $table->id();
            // $table->integer('category_id');  target primary key of category i.e set foreign key here
            $table->string('category_name');
            $table->string('product_name');
            $table->string('product_code')->unique();
            $table->integer('weight');
            $table->string('product_image')->nullable();
            $table->integer('quantity');
            $table->integer('original_price');
            $table->integer('discount');
            $table->integer('sale_price');
            //$table->string('description');
            $table->text('description'); // Allows longer text

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblproducts');
    }
};
