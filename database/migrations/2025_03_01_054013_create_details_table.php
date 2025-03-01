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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('discount')->nullable();
            $table->string('product_name');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('sub_total');
            $table->unsignedBigInteger('qty');
            $table->unsignedBigInteger('total_qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
