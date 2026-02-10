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
            $table->foreignId('inspection_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('item_id')->constrained();

            $table->string('lot_number')->nullable();
            $table->string('allocation')->nullable();

            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('customers');

            $table->foreignId('condition_id')
                ->nullable()
                ->constrained();

            $table->integer('available_qty')->default(0);
            $table->integer('required_qty')->default(0);
            $table->integer('order_qty')->default(0);

            $table->boolean('inspection_required')->default(true);
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
