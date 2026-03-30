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
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('suspended')->nullable()->default(false);
            $table->boolean('deleted')->nullable()->default(false);
            $table->boolean('ended')->nullable()->default(false);
            $table->boolean('has_missing')->nullable()->default(false);
            $table->bigInteger('product_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['suspeded', 'deleted', 'ended', 'has_missing', 'product_id']);
        });
    }
};
