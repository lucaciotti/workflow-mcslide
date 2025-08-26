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
        Schema::create('task_rows', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id')->unsigned()->index()->nullable();
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->bigInteger('product_range_id')->unsigned()->index()->nullable();
            $table->foreign('product_range_id')->references('id')->on('product_ranges')->onDelete('cascade');
            $table->double('qty', 15, 8)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_rows');
    }
};
