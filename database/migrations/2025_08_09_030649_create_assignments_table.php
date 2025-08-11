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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('class')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // teacher
            $table->string('title');
            $table->longText('instructions')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->unsignedInteger('max_points')->default(100);
            $table->enum('status', ['draft','published','closed'])->default('draft');
            $table->string('drive_file_id')->nullable();
            $table->string('link_url')->nullable();
            $table->timestamps();
            $table->index(['class_id','status','due_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
