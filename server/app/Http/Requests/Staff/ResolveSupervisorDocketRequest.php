<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class ResolveSupervisorDocketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && in_array($this->user()->role, ['supervisor', 'admin', 'super_admin'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'action' => ['required', 'string', 'in:approve,modify,reject'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'cbu' => ['nullable', 'string', 'max:30'],
        ];
    }
}
