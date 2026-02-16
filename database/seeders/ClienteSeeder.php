<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Mascota;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cliente 1 con 2 mascotas
        $cliente1 = Cliente::create([
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'dni' => '12345678',
            'telefono' => '987654321',
            'email' => 'juan.perez@example.com',
            'direccion' => 'Av. Principal 123, Lima',
            'estado' => 'activo',
        ]);

        Mascota::create([
            'cliente_id' => $cliente1->id,
            'nombre' => 'Max',
            'especie' => 'Perro',
            'raza' => 'Golden Retriever',
            'edad' => 3,
            'peso' => 30.5,
            'sexo' => 'macho',
            'estado' => 'activo',
        ]);

        Mascota::create([
            'cliente_id' => $cliente1->id,
            'nombre' => 'Luna',
            'especie' => 'Gato',
            'raza' => 'Persa',
            'edad' => 2,
            'peso' => 4.2,
            'sexo' => 'hembra',
            'estado' => 'activo',
        ]);

        // Cliente 2 con 1 mascota
        $cliente2 = Cliente::create([
            'nombres' => 'María Elena',
            'apellidos' => 'Rodríguez López',
            'dni' => '87654321',
            'telefono' => '912345678',
            'email' => 'maria.rodriguez@example.com',
            'direccion' => 'Jr. Los Olivos 456, Miraflores',
            'estado' => 'activo',
        ]);

        Mascota::create([
            'cliente_id' => $cliente2->id,
            'nombre' => 'Rocky',
            'especie' => 'Perro',
            'raza' => 'Labrador',
            'edad' => 5,
            'peso' => 35.0,
            'sexo' => 'macho',
            'estado' => 'activo',
        ]);

        // Cliente 3 sin mascotas
        Cliente::create([
            'nombres' => 'Pedro José',
            'apellidos' => 'Sánchez Martínez',
            'dni' => '45678912',
            'telefono' => '998877665',
            'email' => 'pedro.sanchez@example.com',
            'direccion' => 'Calle Las Flores 789, San Isidro',
            'estado' => 'activo',
        ]);

        // Cliente 4 con 3 mascotas
        $cliente4 = Cliente::create([
            'nombres' => 'Ana Lucía',
            'apellidos' => 'Torres Vega',
            'dni' => '32165498',
            'telefono' => '955443322',
            'email' => 'ana.torres@example.com',
            'direccion' => 'Av. Arequipa 321, Lince',
            'estado' => 'activo',
        ]);

        Mascota::create([
            'cliente_id' => $cliente4->id,
            'nombre' => 'Michi',
            'especie' => 'Gato',
            'raza' => 'Siamés',
            'edad' => 1,
            'peso' => 3.5,
            'sexo' => 'macho',
            'estado' => 'activo',
        ]);

        Mascota::create([
            'cliente_id' => $cliente4->id,
            'nombre' => 'Bella',
            'especie' => 'Perro',
            'raza' => 'Chihuahua',
            'edad' => 4,
            'peso' => 2.8,
            'sexo' => 'hembra',
            'estado' => 'activo',
        ]);

        Mascota::create([
            'cliente_id' => $cliente4->id,
            'nombre' => 'Coco',
            'especie' => 'Ave',
            'raza' => 'Loro',
            'edad' => 6,
            'peso' => 0.5,
            'sexo' => 'macho',
            'estado' => 'activo',
        ]);

        // Cliente inactivo
        $cliente5 = Cliente::create([
            'nombres' => 'Luis Alberto',
            'apellidos' => 'Mendoza Silva',
            'dni' => '78945612',
            'telefono' => '966554433',
            'email' => 'luis.mendoza@example.com',
            'direccion' => 'Calle Lima 654, Surco',
            'estado' => 'inactivo',
        ]);

        Mascota::create([
            'cliente_id' => $cliente5->id,
            'nombre' => 'Firulais',
            'especie' => 'Perro',
            'raza' => 'Mestizo',
            'edad' => 7,
            'peso' => 15.0,
            'sexo' => 'macho',
            'estado' => 'inactivo',
        ]);
    }
}

