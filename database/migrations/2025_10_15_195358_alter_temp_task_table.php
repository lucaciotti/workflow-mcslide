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
        Schema::table('temp_tasks', function (Blueprint $table) {
            $table->bigInteger('workflow_state_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('workflow_state_id')->references('id')->on('workflow_states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temp_tasks', function (Blueprint $table) {
            $table->dropColumn('workflow_state_id');
        });
    }
};
