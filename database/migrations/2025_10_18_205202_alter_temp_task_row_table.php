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
        Schema::table('temp_task_rows', function (Blueprint $table) {
            $table->integer('num_row')->default(0);
            $table->boolean('box_glass')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temp_task_rows', function (Blueprint $table) {
            $table->dropColumn(['box_glass', 'num_row']);
        });
    }
};
