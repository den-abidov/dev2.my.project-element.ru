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
        Schema::create('kits', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();           // 'Старт', 'Стандарт', 'Стратос'
            $table->unsignedTinyInteger('sponsors_count'); // кол-во уровней выплат
            $table->unsignedInteger('monthly_fee');     // абонплата (RUB/UZS/USD, int)
            $table->unsignedInteger('price');           // суммарная цена пакета (RUB/UZS/USD int)
            $table->json('pay_outs');                   // массив целых сумм по уровням
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kits');
    }
};
