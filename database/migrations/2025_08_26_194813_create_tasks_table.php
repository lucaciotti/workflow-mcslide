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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['ord', 'sost'])->default('ord');
            $table->date('date');
            $table->integer('num')->unsigned()->nullable()->default(0);
            $table->bigInteger('customer_id')->unsigned()->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->bigInteger('shipping_address_id')->unsigned()->index()->nullable();
            $table->foreign('shipping_address_id')->references('id')->on('shipping_addresses')->onDelete('cascade');
            $table->string('carrier', 100)->nullable()->default('');
            $table->date('date_shipping')->nullable();
            $table->boolean('box_glass')->nullable()->default(false);
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
        Schema::dropIfExists('tasks');
    }
};
