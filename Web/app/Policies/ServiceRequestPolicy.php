<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    public function view(User $user, ServiceRequest $request): bool
    {
        return $user->isAdmin() || $request->user_id === $user->id;
    }

    public function update(User $user, ServiceRequest $request): bool
    {
        return $user->isAdmin() || ($request->user_id === $user->id && $request->status === 'PENDIENTE');
    }

    public function delete(User $user, ServiceRequest $request): bool
    {
        return $user->isAdmin() || ($request->user_id === $user->id && $request->status === 'PENDIENTE');
    }

    public function manage(User $user): bool
    {
        return $user->isAdmin();
    }
}
