<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffVerificationController extends Controller
{
    /**
     * List all verification requests for Supervisors and Validators.
     * - Supervisor / Admin: ve todas las solicitudes de la provincia con alerta de cobertura.
     * - Validator: ve sus solicitudes asignadas o las de su departamento de residencia.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $isSupervisorOrAdmin = in_array($user->role, ['supervisor', 'admin', 'super_admin'], true);

        $query = VerificationRequest::with([
            'property.department',
            'property.images',
            'property.amenities',
            'client:id,name,email,phone,dni',
            'validator:id,name,email,phone',
            'supervisor:id,name',
        ])->orderBy('created_at', 'desc');

        // Si es validador y no supervisor, filtrar por sus asignadas o pendientes generales
        if (! $isSupervisorOrAdmin && $user->role === 'validator') {
            $onlyMine = $request->boolean('only_my_assignments', false);
            if ($onlyMine) {
                $query->where('validator_id', $user->id);
            } else {
                $query->where(function ($q) use ($user) {
                    $q->where('validator_id', $user->id)
                      ->orWhereNull('validator_id');
                });
            }
        }

        // Filtro por tab/estado
        $status = $request->query('status');
        if ($status && $status !== 'todos') {
            if ($status === 'pendientes') {
                $query->whereIn('status', ['pending', 'assigned']);
            } elseif ($status === 'proceso') {
                $query->whereIn('status', ['visited', 'under_supervision']);
            } elseif ($status === 'validados') {
                $query->where('status', 'approved');
            } elseif ($status === 'rechazados') {
                $query->where('status', 'rejected');
            } else {
                $query->where('status', $status);
            }
        }

        // Filtro por departamento
        $department = $request->query('department');
        if ($department && $department !== 'Todos') {
            $query->whereHas('property.department', function ($q) use ($department) {
                $q->where('name', 'like', "%{$department}%")
                  ->orWhere('slug', $department);
            });
        }

        $items = $query->get();

        // Obtener la lista de validadores matriculados activos para saber qué departamentos tienen cobertura
        $validators = User::where('role', 'validator')
            ->where('is_active', true)
            ->get(['id', 'name', 'phone', 'origin_city', 'origin_province']);

        // Nombres de ciudades o departamentos cubiertos por validadores activos
        $validatorCities = $validators->pluck('origin_city')->filter()->map(fn ($c) => strtolower(trim((string) $c)))->all();

        // Mapear cada solicitud agregando indicador de alerta si la zona no tiene validador
        $mapped = $items->map(function ($vr) use ($validators, $validatorCities) {
            $prop = $vr->property;
            $dept = $prop?->department;
            $deptName = strtolower(trim((string) ($dept?->name ?? '')));

            // Tiene validador si ya fue asignado o si hay algún validador activo radicado en ese departamento
            $hasLocalValidator = ! empty($vr->validator_id) || (
                ! empty($deptName) && in_array($deptName, $validatorCities, true)
            );

            return [
                'id' => (string) $vr->id,
                'code' => 'SOL-TS-' . str_pad((string) $vr->id, 4, '0', STR_PAD_LEFT),
                'property_id' => $vr->property_id,
                'propertyTitle' => $prop?->title ?? 'Propiedad en trámite',
                'property_type' => $prop?->property_type ?? 'Quinta',
                'department' => $dept?->name ?? 'San Juan',
                'department_id' => $dept?->id,
                'address' => $prop?->location ?? 'Ubicación a auditar',
                'ownerName' => $vr->client?->name ?? 'Anfitrión',
                'ownerDni' => $vr->client?->dni ?? 'S/DNI',
                'ownerPhone' => $vr->client?->phone ?? 'S/Tel',
                'hoursAgo' => $vr->created_at ? $vr->created_at->diffForHumans() : 'Reciente',
                'status' => $vr->status,
                'priority' => $vr->status === 'pending' && ! $vr->validator_id ? 'urgent' : 'normal',
                'photosCount' => $prop?->images?->count() ?? 0,
                'imageUrl' => $prop?->images?->first()?->image_path ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80',
                'amenities' => $prop?->amenities?->pluck('amenity')->all() ?? [],
                'notes' => $vr->notes,
                'security_token' => $vr->security_token,
                'validator_id' => $vr->validator_id,
                'validator_name' => $vr->validator?->name,
                'supervisor_id' => $vr->supervisor_id,
                'hasLocalValidator' => $hasLocalValidator,
                'coverageAlert' => ! $hasLocalValidator && ! $vr->validator_id
                    ? 'Zona sin validador local asignado. El supervisor debe designar un validador limítrofe.'
                    : null,
                'payout_account_type' => $vr->payout_account_type ?? $vr->client?->payout_account_type ?? 'cbu',
                'payout_account_number' => $vr->payout_account_number ?? $vr->client?->payout_account_number ?? null,
                'payout_bank_name' => $vr->payout_bank_name ?? $vr->client?->payout_bank_name ?? 'Banco San Juan',
                'payout_verified' => (bool) ($vr->payout_verified ?? $vr->client?->payout_verified ?? false),
                'visit_date' => $vr->visit_date ? $vr->visit_date->format('d/m/Y') : null,
                'checklist' => $vr->checklist,
                'rejection_reason' => $vr->rejection_reason,
            ];
        });

        return response()->json([
            'data' => $mapped,
            'metrics' => [
                'pending' => VerificationRequest::where('status', 'pending')->count(),
                'inProgress' => VerificationRequest::whereIn('status', ['assigned', 'visited', 'under_supervision'])->count(),
                'approved' => VerificationRequest::where('status', 'approved')->count(),
                'rejected' => VerificationRequest::where('status', 'rejected')->count(),
            ],
            'available_validators' => $isSupervisorOrAdmin ? $validators : [],
        ]);
    }

    /**
     * Designar o reasignar validador matriculado por parte del Supervisor/Admin.
     */
    public function assignValidator(int $id, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'validator_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $validator = User::where('role', 'validator')->findOrFail($validated['validator_id']);
        $verificationRequest = VerificationRequest::findOrFail($id);

        $token = $verificationRequest->security_token;
        if (! $token) {
            $token = 'TS-' . str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        }

        $verificationRequest->update([
            'validator_id' => $validator->id,
            'supervisor_id' => $request->user()->id,
            'status' => 'assigned',
            'security_token' => $token,
            'notes' => $validated['notes'] ?? $verificationRequest->notes,
        ]);

        return response()->json([
            'message' => "Validador {$validator->name} designado exitosamente para la inspección.",
            'verification_request' => $verificationRequest->load(['validator', 'supervisor']),
        ]);
    }

    /**
     * Resolver dictamen de supervisión (aprobar, pedir modificaciones, rechazar).
     */
    public function resolveDocket(int $id, \App\Http\Requests\Staff\ResolveSupervisorDocketRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $action = $validated['action'];
        $notes = $validated['notes'] ?? '';

        $verificationRequest = VerificationRequest::with(['property', 'client', 'validator'])->findOrFail($id);
        $property = $verificationRequest->property;

        if ($action === 'approve') {
            $verificationRequest->update([
                'status' => 'approved',
                'supervisor_id' => $request->user()->id,
                'notes' => $notes ?: 'Auditoría in situ 100% conforme. Cumple normativa provincial Res. 182-MTyC/24. Sello San Juan Seguro habilitado para publicación y reserva inmediata.',
            ]);

            if ($property) {
                $propertyDept = $property->department?->slug ?? 'SAN';
                $deptCode = strtoupper(substr($propertyDept, 0, 3));
                $actNumber = $property->verification_certificate_number ?: "Acta #{$property->id}-{$deptCode}/26";

                $property->update([
                    'verification_status' => 'verified',
                    'publication_status' => 'published',
                    'verified_at' => now(),
                    'verification_certificate_number' => $actNumber,
                ]);
            }

            $message = 'Expediente aprobado con éxito. Firma digital aplicada y Sello QR Oficial emitido.';
        } elseif ($action === 'modify') {
            $verificationRequest->update([
                'status' => 'under_supervision',
                'supervisor_id' => $request->user()->id,
                'rejection_reason' => $notes ?: 'Requerimiento técnico de subsanación dentro de las 48/72hs hábiles.',
            ]);

            $message = 'Plazo de subsanación de 48/72hs notificado al propietario y al validador.';
        } else {
            // reject
            $verificationRequest->update([
                'status' => 'rejected',
                'supervisor_id' => $request->user()->id,
                'rejection_reason' => $notes ?: 'Dictamen técnico desestimado por inconsistencias o incumplimiento normativo.',
            ]);

            if ($property) {
                $property->update([
                    'verification_status' => 'rejected',
                    'publication_status' => 'draft',
                ]);
            }

            $message = 'Dictamen rechazado y archivado con causa vinculante en el registro provincial.';
        }

        return response()->json([
            'message' => $message,
            'verification_request' => $verificationRequest->fresh(['property.department', 'client', 'validator', 'supervisor']),
        ]);
    }
}
