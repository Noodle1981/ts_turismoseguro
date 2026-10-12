<?php

declare(strict_types=1);

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientPropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'department_id' => ['sometimes', 'required', 'exists:departments,id'],
            'locality_id' => ['nullable', 'exists:localities,id'],
            'property_type' => ['sometimes', 'required', 'string', 'max:100'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'price' => ['sometimes', 'required', 'integer', 'min:1'],
            'price_type' => ['sometimes', 'required', 'string', 'in:por día,/noche'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:1'],
            'host_whatsapp' => ['nullable', 'string', 'max:50'],
            'host_phone' => ['nullable', 'string', 'max:50'],

            // Tarifas multimodales
            'tariff_lunes_jueves' => ['nullable', 'integer', 'min:0'],
            'tariff_viernes_domingo' => ['nullable', 'integer', 'min:0'],
            'tariff_sabado_feriados' => ['nullable', 'integer', 'min:0'],
            'tariff_evento_especial' => ['nullable', 'integer', 'min:0'],
            'tariff_pernocte_camas' => ['nullable', 'integer', 'min:0'],
            'tariff_pernocte_carpas' => ['nullable', 'integer', 'min:0'],
            'tariff_finde_completo' => ['nullable', 'integer', 'min:0'],
            'tariff_fiesta_nocturna' => ['nullable', 'integer', 'min:0'],

            // Horarios y Días
            'schedule_start' => ['nullable', 'string', 'max:10'],
            'schedule_end' => ['nullable', 'string', 'max:10'],
            'active_days' => ['nullable', 'array'],
            'active_days.*' => ['integer', 'between:0,6'],

            // Visibilidad y Vacaciones
            'is_listing_visible' => ['nullable', 'boolean'],
            'pause_mode' => ['nullable', 'string', 'in:indefinite,vacation'],
            'pause_start_date' => ['nullable', 'date'],
            'pause_end_date' => ['nullable', 'date', 'after_or_equal:pause_start_date'],

            // Características y Reglas
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string', 'max:50'],
            'rules' => ['nullable', 'array'],
            'rules.*' => ['string', 'max:255'],
        ];
    }
}
