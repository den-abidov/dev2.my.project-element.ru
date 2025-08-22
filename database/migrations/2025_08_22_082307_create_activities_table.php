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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            // одна запись на пользователя
            $table->foreignId('user_id')->unique()
                ->nullable()               // для SET NULL при удалении
                ->constrained('users')
                ->nullOnDelete();          // если пользователя удалили — оставить след, но обнулить FK

            // флаги прогресса по пакетным переводам
            $table->boolean('has_selected_kit')->default(false);
            $table->boolean('has_made_some_payments')->default(false);
            $table->boolean('has_made_all_payments')->default(false);

            // подписка (ежемесячная активность)
            $table->date('fee_payment_due_date')->nullable();   // дата до которой активна подписка
            $table->boolean('has_paid_this_month_fee')->default(false);

            // производные/служебные поля (на будущее)
            $table->unsignedInteger('level')->default(0);       // кэш «глубины» в дереве (ежесуточный пересчёт)
            $table->json('meta')->nullable();                   // запас под доп. маркеры

            $table->timestamps();

            // удобные индексы для выборок
            $table->index(['has_made_all_payments', 'has_paid_this_month_fee']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
