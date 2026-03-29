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
        Schema::create('workflow_state_erp_states', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('erp_state_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('erp_state_id')->references('id')->on('erp_states')->onDelete('cascade');
            $table->bigInteger('workflow_state_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('workflow_state_id')->references('id')->on('workflow_states')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_state_erp_states');
    }
};
