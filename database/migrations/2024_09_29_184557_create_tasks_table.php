<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TaskType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attached_to')->nullable();
            $table->foreign('attached_to')->references('id')->on('tasks');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('status')->nullable()->default('to-do');
            $table->unsignedBigInteger('owner_id');
            $table->string('type')->default('main');
            $table->timestamps();
            $table->foreign('owner_id')->references('id')->on('users');
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
