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
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('especie', 50);
            $table->string('raza', 100)->nullable();
            $table->integer('edad')->unsigned()->nullable();
            $table->decimal('peso', 6, 2)->unsigned()->nullable();
            $table->enum('sexo', ['macho', 'hembra'])->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            $table->softDeletes();

            // Índices para optimizar consultas
            $table->index('cliente_id');
            $table->index('estado');
            $table->index(['cliente_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};

