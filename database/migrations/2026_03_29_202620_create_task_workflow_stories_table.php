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
        Schema::create('task_workflow_stories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->bigInteger('workflow_state_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('workflow_state_id')->references('id')->on('workflow_states')->onDelete('cascade');
            $table->text('comment')->nullable()->default('');
            $table->date('start')->nullable()->useCurrent();
            $table->date('end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_workflow_stories');
    }
};
