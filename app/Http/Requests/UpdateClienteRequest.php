<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clienteId = $this->route('cliente');

        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'dni' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
                Rule::unique('clientes', 'dni')
                    ->ignore($clienteId)
                    ->whereNull('deleted_at')
            ],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'estado' => ['nullable', 'string', Rule::in(['activo', 'inactivo'])],

            // Validación de mascotas
            'mascotas' => ['nullable', 'array'],
            'mascotas.*.id' => ['nullable', 'integer', 'exists:mascotas,id'],
            'mascotas.*.nombre' => ['required_with:mascotas', 'string', 'max:100'],
            'mascotas.*.especie' => ['required_with:mascotas', 'string', 'max:50'],
            'mascotas.*.raza' => ['nullable', 'string', 'max:100'],
            'mascotas.*.edad' => ['nullable', 'integer', 'min:0', 'max:100'],
            'mascotas.*.peso' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'mascotas.*.sexo' => ['nullable', 'string', Rule::in(['macho', 'hembra'])],
            'mascotas.*.estado' => ['nullable', 'string', Rule::in(['activo', 'inactivo'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombres.required' => 'El campo nombres es obligatorio.',
            'nombres.max' => 'El campo nombres no debe exceder los 100 caracteres.',
            'apellidos.required' => 'El campo apellidos es obligatorio.',
            'apellidos.max' => 'El campo apellidos no debe exceder los 100 caracteres.',
            'dni.required' => 'El campo DNI es obligatorio.',
            'dni.unique' => 'El DNI ya está registrado en el sistema.',
            'dni.regex' => 'El DNI debe contener solo números.',
            'email.email' => 'El email debe ser una dirección de correo válida.',
            'estado.in' => 'El estado debe ser activo o inactivo.',

            // Mensajes para mascotas
            'mascotas.*.id.exists' => 'La mascota especificada no existe.',
            'mascotas.*.nombre.required_with' => 'El nombre de la mascota es obligatorio.',
            'mascotas.*.especie.required_with' => 'La especie de la mascota es obligatoria.',
            'mascotas.*.edad.integer' => 'La edad debe ser un número entero.',
            'mascotas.*.edad.min' => 'La edad debe ser mayor o igual a 0.',
            'mascotas.*.peso.numeric' => 'El peso debe ser un número.',
            'mascotas.*.peso.min' => 'El peso debe ser mayor o igual a 0.',
            'mascotas.*.sexo.in' => 'El sexo debe ser macho o hembra.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'dni' => 'DNI',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
            'direccion' => 'dirección',
            'estado' => 'estado',
        ];
    }
}

