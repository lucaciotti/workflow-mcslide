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
        Schema::create('temp_task_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temp_task_id')->nullable();
            $table->boolean('imported')->default(false);
            $table->bigInteger('product_range_id')->unsigned()->index()->nullable();
            $table->foreign('product_range_id')->references('id')->on('product_ranges')->onDelete('cascade');
            $table->double('qty', 15, 8)->nullable()->default(0);
            $table->timestamps();


            $table->foreign('temp_task_id')->references('id')->on('temp_tasks')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_task_rows');
    }
};
