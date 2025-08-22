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
        Schema::create('sponsors', function (Blueprint $table) {
             $table->id();

            // один к одному с users: каждая запись описывает «спонсорские данные» пользователя
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // непосредственный аплайн (верхний спонсор)
            $table->foreignId('sponsor_id')->nullable()->constrained('users')->nullOnDelete();

            // служебные поля
            $table->unsignedInteger('level')->default(0);               // глубина в дереве
            $table->unsignedSmallInteger('sponsors_count')->default(0); // размер цепочки

            // расчётные структуры (храним как JSON)
            $table->json('sponsor_ids_all')->nullable();       // вся восходящая цепочка
            $table->json('sponsor_ids_active')->nullable();    // активные из цепочки
            $table->json('sponsor_ids_payee')->nullable();     // актуальные получатели
            $table->json('sponsor_ids_payee_all')->nullable(); // расширенная структура
            $table->json('payments_status')->nullable();       // обязательства к исходящим переводам

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};
