<?php

use App\Enums\ConversionOrigin;
use App\Enums\HomeworkChange;
use App\Enums\HomeworkStatus;
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
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->unsignedBigInteger('admin');
            $table->unsignedBigInteger('client');
            $table->enum('conversion', ConversionOrigin::cases())->default(ConversionOrigin::Client);
            $table->unsignedBigInteger('type_homework');
            $table->dateTime('client_delivery');
            $table->dateTime('specialist_delivery');
            $table->string('drive_link')->nullable();
            $table->enum('status', HomeworkStatus::cases())->default(HomeworkStatus::Unassigned);
            $table->enum('change', HomeworkChange::cases())->default(HomeworkChange::NoChange);
            $table->unsignedBigInteger('specialist')->nullable();
            $table->float('final_price', 2)->default(0.00);
            $table->float('specialist_payment')->default(0.00);
            $table->float('proft', 2)->default(0.0);
            $table->float('amount_paid', 2)->default(0.0);
            $table->timestamps();

            $table->foreign('admin')->references('id')->on('users');
            $table->foreign('client')->references('id')->on('clients');
            $table->foreign('type_homework')->references('id')->on('type_homework');
            $table->foreign('specialist')->references('id')->on('specialists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework');
    }
};
