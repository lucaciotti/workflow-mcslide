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
        Schema::create('task_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id')->unsigned()->index()->nullable();
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->bigInteger('attribute_id')->unsigned()->index()->nullable();
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->double('num_value', 15, 8)->nullable();
            $table->string('string_value', 255)->nullable();
            $table->boolean('bool_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_attribute_values');
    }
};
