<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignServiceRequest;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Services\RequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RequestController extends Controller
{
    public function __construct(private readonly RequestService $service) {}

    public function index(Request $request): JsonResponse
    {
        $query = ServiceRequest::query()->with(['type', 'student:id,name,email', 'assignee:id,name,email'])->latest();
        if (! $request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }
        $query->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status));
        $query->when($request->string('priority')->toString(), fn ($q, $priority) => $q->where('priority', $priority));

        return response()->json($query->paginate(15));
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $serviceRequest = $this->service->create($request->user(), $request->validated());

        return response()->json(['message' => 'Solicitud registrada.', 'data' => $this->detail($serviceRequest)], 201);
    }

    public function show(ServiceRequest $serviceRequest): JsonResponse
    {
        Gate::authorize('view', $serviceRequest);

        return response()->json(['data' => $this->detail($serviceRequest)]);
    }

    public function update(UpdateServiceRequest $request, ServiceRequest $serviceRequest): JsonResponse
    {
        Gate::authorize('update', $serviceRequest);
        $serviceRequest = $this->service->update($serviceRequest, $request->validated());

        return response()->json(['message' => 'Solicitud actualizada.', 'data' => $this->detail($serviceRequest)]);
    }

    public function destroy(ServiceRequest $serviceRequest): JsonResponse
    {
        Gate::authorize('delete', $serviceRequest);
        $serviceRequest->delete();

        return response()->json(['message' => 'Solicitud eliminada.']);
    }

    public function assign(AssignServiceRequest $request, ServiceRequest $serviceRequest): JsonResponse
    {
        $assignee = User::with('role')->findOrFail($request->integer('assigned_to'));
        if (! $assignee->isAdmin()) {
            return response()->json(['message' => 'El responsable debe ser administrativo.'], 422);
        }

        $serviceRequest = $this->service->assign($serviceRequest, $assignee, $request->user());

        return response()->json(['message' => 'Responsable asignado.', 'data' => $this->detail($serviceRequest)]);
    }

    public function changeStatus(ChangeStatusRequest $request, ServiceRequest $serviceRequest): JsonResponse
    {
        $serviceRequest = $this->service->changeStatus(
            $serviceRequest,
            $request->string('status')->toString(),
            $request->user(),
            $request->input('comment'),
        );

        return response()->json(['message' => 'Estado actualizado.', 'data' => $this->detail($serviceRequest)]);
    }

    private function detail(ServiceRequest $request): ServiceRequest
    {
        return $request->load([
            'type', 'student:id,name,email', 'assignee:id,name,email',
            'comments.user:id,name', 'evidences.user:id,name', 'statusHistory.user:id,name',
        ]);
    }
}
