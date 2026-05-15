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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('order_sn')->unique(); // 易读的订单编号（如：20231024xxx）
            $table->foreignId('user_id')->index();

            $table->decimal('total_amount', 12, 2);
            $table->decimal('pay_amount', 12, 2);

            $table->string('status')->default('pending');
            $table->json('address_snapshot');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // 软删除，防止误删
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
