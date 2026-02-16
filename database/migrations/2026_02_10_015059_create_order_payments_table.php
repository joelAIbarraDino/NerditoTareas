<?php

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
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_homework');
            $table->float('amount', 2)->default(0.0);
            $table->string('mp_link');
            $table->enum('status', PaymentStatus::cases())->default(PaymentStatus::Pending);
            $table->timestamps();

            $table->foreign('id_homework')->references('id')->on('homework');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
