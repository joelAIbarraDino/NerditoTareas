<?php

use App\Enums\DocumentType;
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
        Schema::create('homework_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_homework');
            $table->enum('type', DocumentType::cases())->default(DocumentType::HomeworkDocument);
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('path');
            $table->timestamps();

            $table->foreign('id_homework')->references('id')->on('homework');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework_documents');
    }
};
