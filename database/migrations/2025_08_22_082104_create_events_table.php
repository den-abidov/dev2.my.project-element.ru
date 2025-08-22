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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Кто инициировал (может быть системное событие -> nullable + SET NULL при удалении)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Классификация и полезная нагрузка
            $table->string('event_name', 100)->index(); // например: PaymentCompleted, SubscriptionExtended
            $table->json('event_value')->nullable();    // payload (IDs, суммы, статусы)
            $table->string('event_description', 500)->nullable();

            // Временные поля (и датой, и датой-временем, как в ТЗ)
            $table->date('date')->nullable();
            $table->dateTime('date_time')->nullable();

            $table->timestamps();

            // Частые выборки по типу события и пользователю
            $table->index(['user_id', 'event_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
