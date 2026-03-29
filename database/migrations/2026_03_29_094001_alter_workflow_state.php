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
        Schema::table('workflow_states', function (Blueprint $table) {
            $table->dropColumn('workflow_state_category_id');
            $table->bigInteger('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->renameColumn('is_gate', 'enable_gate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_states', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->bigInteger('workflow_state_category_id')->unsigned()->index()->nullable();
            $table->foreign('workflow_state_category_id')->references('id')->on('workflow_state_categories')->onDelete('cascade');

            $table->renameColumn('enable_gate', 'is_gate');
        });
    }
};
