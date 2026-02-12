<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained('inspections')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->cascadeOnDelete();
            $table->foreignId('lot_id')->constrained('lots')->cascadeOnDelete();
            $table->foreignId('allocation_id')->nullable()->constrained('master_data')->cascadeOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('master_data')->cascadeOnDelete();
            $table->foreignId('condition_id')->nullable()->constrained('master_data')->cascadeOnDelete();
            $table->integer('initial_stock')->nullable();
            $table->integer('qty_required')->nullable();
            $table->integer('price')->nullable();
            $table->integer('subtotal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_items');
    }
};
