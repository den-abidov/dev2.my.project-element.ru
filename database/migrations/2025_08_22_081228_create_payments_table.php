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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // отправитель и получатель (если юзера удалят — не теряем запись о платеже)
            $table->foreignId('user_id_from')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_id_to')->nullable()->constrained('users')->nullOnDelete();

            // доменный ID обязательства (детерминированный): user_id_from:index:user_id_to:sum
            $table->string('payment_id', 191)->index();

            // сумма в рублях (целое, согласно ADR-001)
            $table->unsignedInteger('amount');

            // используемый шлюз и внешний ID транзакции в PSP
            $table->string('gateway', 50);                       // 'yoomoney' | 'qiwi' | 'advcash'
            $table->string('gateway_payment_id', 191)->nullable()->unique();

            // статус платежа (истина — по вебхуку)
            $table->string('status', 32)->default('initiated');  // initiated|succeeded|failed|...
            $table->timestamp('webhook_received_at')->nullable();
            $table->string('webhook_last_status', 64)->nullable();

            $table->timestamps();

            // частые выборки
            $table->index(['user_id_from', 'user_id_to']);
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
