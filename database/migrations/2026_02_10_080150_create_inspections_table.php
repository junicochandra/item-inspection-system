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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->unique();

            $table->foreignId('location_id')->constrained();
            $table->foreignId('service_type_id')->constrained();
            $table->integer('scope_of_work_id');
            $table->foreignId('customer_id')->nullable()->constrained();

            $table->date('submitted_at')->nullable();
            $table->date('estimated_completion_date')->nullable();

            $table->string('related_to')->nullable();
            $table->boolean('charge_to_customer')->default(false);

            $table->integer('status_id')->default(1);

            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
