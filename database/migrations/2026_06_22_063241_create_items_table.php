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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('image')->nullable();
            
            // Dual Unit Pricing
            $table->string('primary_unit')->default('Pcs'); // E.g., Roll
            $table->string('secondary_unit')->nullable(); // E.g., Meter
            $table->decimal('conversion_factor', 10, 2)->default(1); // E.g., 100 (1 Roll = 100 Meters)
            
            // Warranty
            $table->boolean('has_warranty')->default(false);
            $table->integer('warranty_months')->default(0);

            $table->foreignId('category_id')->nullable();
            $table->foreignId('sub_category_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
