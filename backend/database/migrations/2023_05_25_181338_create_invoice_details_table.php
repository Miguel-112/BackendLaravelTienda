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
    Schema::create('invoice_details', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('cantidad');
        $table->decimal('iva');
        $table->decimal('purchase_price');
        $table->decimal('sale_price');
        $table->decimal('saleprice_total');
        $table->unsignedBigInteger('invoice_id');
        $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        $table->integer('id_motorcycle_parts');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
