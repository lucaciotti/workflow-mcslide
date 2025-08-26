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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attribute_category_id')->unsigned()->index()->nullable();
            $table->foreign('attribute_category_id')->references('id')->on('attribute_categories')->onDelete('cascade');
            $table->string('name', 100);
            $table->enum('type', ['num', 'string', 'bool', 'date', 'note'])->nullable()->default('string');
            $table->timestamps();
            $table->unique(['attribute_category_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
