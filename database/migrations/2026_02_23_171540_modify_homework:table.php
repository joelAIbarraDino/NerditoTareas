<?php

use App\Enums\ConversionOrigin;
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
        Schema::table('homework', function (Blueprint $table) {
            $table->dropColumn('conversion');
            $table->dropColumn('private_order_id');
            $table->string('change_notes')->after('description')->nullable();
            $table->string('drive_link')->after('specialist_delivery')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homework', function (Blueprint $table) {
            $table->dropColumn('drive_link');
            $table->dropColumn('change_notes');
            $table->string('private_order_id')->after('order_id')->nullable();
            $table->enum('conversion', ConversionOrigin::cases())->default(ConversionOrigin::Client)->after('client');
        });
    }
};
