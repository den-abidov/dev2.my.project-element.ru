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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();

            // Владелец подписки
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Период подписки
            $table->date('date');     // начало периода
            $table->date('to_date');  // конец периода

            // Сумма в рублях (целое)
            $table->unsignedInteger('amount');

            // Дет. ID абонплаты и внешний ID транзакции ПС
            $table->string('fee_id', 191)->unique();       // user_id:{start}:{amount}:{end}
            $table->string('fee_payment_id', 191)->nullable()->index(); // внешний ID у PSP

            // Линкуем на внутренний платеж (опционально)
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
