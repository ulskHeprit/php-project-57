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
        Schema::create('task_label', function (Blueprint $table) {
            $table->bigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->bigInteger('label_id');
            $table->foreign('label_id')->references('id')->on('labels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_label');
    }
};
