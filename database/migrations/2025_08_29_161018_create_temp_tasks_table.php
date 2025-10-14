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
        Schema::create('temp_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_import_file_id');
            $table->unsignedBigInteger('task_id')->nullable();
            $table->boolean('imported')->default(false);
            $table->date('date_last_import')->nullable();
            $table->boolean('selected')->default(false);
            $table->boolean('warning')->default(false);
            $table->text('error')->nullable();
            $table->integer('num_row')->default(0);
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


            $table->foreign('task_import_file_id')->references('id')->on('task_import_files')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_tasks');
    }
};
