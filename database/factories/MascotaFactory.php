<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Mascota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mascota>
 */
class MascotaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mascota::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $especies = ['Perro', 'Gato', 'Ave', 'Conejo', 'Hamster'];
        $especie = fake()->randomElement($especies);

        $razas = [
            'Perro' => ['Labrador', 'Golden Retriever', 'Bulldog', 'Beagle', 'Chihuahua'],
            'Gato' => ['Persa', 'Siamés', 'Angora', 'Bengalí', 'Esfinge'],
            'Ave' => ['Loro', 'Canario', 'Cacatúa', 'Periquito'],
            'Conejo' => ['Enano', 'Gigante', 'Angora'],
            'Hamster' => ['Dorado', 'Ruso', 'Chino'],
        ];

        return [
            'cliente_id' => Cliente::factory(),
            'nombre' => fake()->firstName(),
            'especie' => $especie,
            'raza' => fake()->randomElement($razas[$especie]),
            'edad' => fake()->numberBetween(0, 15),
            'peso' => fake()->randomFloat(2, 0.5, 50),
            'sexo' => fake()->randomElement(['macho', 'hembra']),
            'estado' => fake()->randomElement(['activo', 'inactivo']),
        ];
    }

    /**
     * Indicate that the mascota is active.
     */
    public function activo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'activo',
        ]);
    }

    /**
     * Indicate that the mascota is inactive.
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'inactivo',
        ]);
    }

    /**
     * Indicate that the mascota is a dog.
     */
    public function perro(): static
    {
        return $this->state(fn (array $attributes) => [
            'especie' => 'Perro',
            'raza' => fake()->randomElement(['Labrador', 'Golden Retriever', 'Bulldog']),
        ]);
    }

    /**
     * Indicate that the mascota is a cat.
     */
    public function gato(): static
    {
        return $this->state(fn (array $attributes) => [
            'especie' => 'Gato',
            'raza' => fake()->randomElement(['Persa', 'Siamés', 'Angora']),
        ]);
    }
}

