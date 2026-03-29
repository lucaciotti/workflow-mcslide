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
        Schema::create('workflow_state_product_ranges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('workflow_state_id')->unsigned()->index()->nullable();
            $table->foreign('workflow_state_id')->references('id')->on('workflow_states')->onDelete('cascade');
            $table->bigInteger('product_range_id')->unsigned()->index()->nullable();
            $table->foreign('product_range_id')->references('id')->on('product_ranges')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_state_product_ranges');
    }
};
