<?php

namespace App\Services;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RequestService
{
    private const TRANSITIONS = [
        'PENDIENTE' => ['ASIGNADA'],
        'ASIGNADA' => ['EN_PROCESO'],
        'EN_PROCESO' => ['RESUELTA'],
        'RESUELTA' => ['CERRADA', 'EN_PROCESO'],
        'CERRADA' => [],
    ];

    public function create(User $owner, array $data, ?User $actor = null): ServiceRequest
    {
        $actor ??= $owner;

        return DB::transaction(function () use ($owner, $actor, $data) {
            $request = ServiceRequest::create([
                ...$data,
                'tracking_code' => 'CC-'.now()->format('Y').'-'.Str::upper(Str::random(8)),
                'user_id' => $owner->id,
                'status' => 'PENDIENTE',
            ]);

            $request->statusHistory()->create([
                'user_id' => $actor->id,
                'old_status' => null,
                'new_status' => 'PENDIENTE',
                'comment' => 'Solicitud registrada.',
            ]);

            return $request->fresh();
        });
    }

    public function update(ServiceRequest $request, array $data): ServiceRequest
    {
        $request->update($data);

        return $request->fresh();
    }

    public function assign(ServiceRequest $request, User $assignee, User $actor): ServiceRequest
    {
        return DB::transaction(function () use ($request, $assignee, $actor) {
            $oldStatus = $request->status;
            $request->assigned_to = $assignee->id;
            if ($request->status === 'PENDIENTE') {
                $request->status = 'ASIGNADA';
            }
            $request->save();

            $request->statusHistory()->create([
                'user_id' => $actor->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'comment' => "Responsable asignado: {$assignee->name}.",
            ]);

            return $request->fresh();
        });
    }

    public function changeStatus(ServiceRequest $request, string $newStatus, User $actor, ?string $comment = null): ServiceRequest
    {
        if (! in_array($newStatus, self::TRANSITIONS[$request->status] ?? [], true)) {
            throw ValidationException::withMessages(['status' => "No se puede cambiar de {$request->status} a {$newStatus}."]);
        }

        if ($newStatus === 'EN_PROCESO' && ! $request->assigned_to) {
            throw ValidationException::withMessages(['status' => 'Asigna un responsable antes de iniciar el trabajo.']);
        }

        return DB::transaction(function () use ($request, $newStatus, $actor, $comment) {
            $oldStatus = $request->status;
            $request->update([
                'status' => $newStatus,
                'closed_at' => $newStatus === 'CERRADA' ? now() : null,
            ]);

            $request->statusHistory()->create([
                'user_id' => $actor->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'comment' => $comment,
            ]);

            return $request->fresh();
        });
    }
}
