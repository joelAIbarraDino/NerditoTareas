<?php

use App\Enums\MercadoPagoAccount;
use App\Enums\PaymentMethod;
use App\Enums\PaymentProvider;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->enum('payment_provider', PaymentProvider::cases())->default(PaymentProvider::MercadoPago);
            $table->float('amount', 2)->default(0.00);
            $table->string('payment_id')->nullable();
            $table->enum('destination_account', MercadoPagoAccount::cases())->default(MercadoPagoAccount::MercadoPago1);
            $table->enum('method', PaymentMethod::cases())->default(PaymentMethod::BankTransfer);
            $table->enum('status', PaymentStatus::cases())->default(PaymentStatus::Pending);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('homework');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
