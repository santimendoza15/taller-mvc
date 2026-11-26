<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('cliente', 100);
            $table->string('servicio', 50);
            $table->date('fecha');
            $table->string('hora', 5);
            $table->boolean('confirmada')->default(false);
            $table->timestamps();

            // No puede haber dos reservas en la misma fecha y hora
            $table->unique(['fecha', 'hora']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
