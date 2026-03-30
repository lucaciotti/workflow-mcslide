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
        Schema::create('task_missings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->bigInteger('component_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('component_id')->references('id')->on('components')->onDelete('cascade');
            $table->bigInteger('supplier_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->boolean('stock_available')->nullable()->default(false);
            $table->boolean('stock_not_available')->nullable()->default(false);

            $table->boolean('supplier_request')->nullable()->default(false);
            $table->string('ref_supplier_doc')->nullable()->default('');
            $table->date('ref_supplier_date_start')->nullable();
            $table->date('ref_supplier_date_end')->nullable();
            $table->boolean('purchased')->nullable()->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_missings');
    }
};
