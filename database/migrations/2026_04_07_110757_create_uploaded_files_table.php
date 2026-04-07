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
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('host_id');
            $table->string('source_path');
            $table->string('name')->unique();
            $table->integer('size_bytes');
            $table->integer('zip_size_bytes');
            $table->integer('number_of_file');
            $table->enum('dataset_type', ["image","video","code","text","tabular"]);
            $table->string('hash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_files');
    }
};
