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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('invoice_detail')->nullable();
            $table->unsignedBigInteger('total');
            $table->date('invoice_date');
            $table->unsignedBigInteger('nominal_charge');
            $table->unsignedBigInteger('charge');
            $table->unsignedBigInteger('total_final');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
