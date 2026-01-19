<?php

use App\Enums\PaymentSpecialistType;
use App\Enums\PaymentStatus;
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
        Schema::create('payment_specialists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_specialist');
            $table->unsignedBigInteger('id_homework');
            $table->float('amount', 2)->default(0.00);
            $table->enum('type', PaymentSpecialistType::cases())->default(PaymentSpecialistType::Payment);
            $table->enum('status', PaymentStatus::cases())->default(PaymentStatus::Pending);
            $table->timestamps();

            $table->foreign('id_specialist')->references('id')->on('specialists');
            $table->foreign('id_homework')->references('id')->on('homework');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_specialists');
    }
};
