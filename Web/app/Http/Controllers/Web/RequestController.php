<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignServiceRequest;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\RequestType;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Services\RequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function __construct(private readonly RequestService $service) {}

    public function index(Request $request): View
    {
        $requests = ServiceRequest::with(['type', 'student', 'assignee'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->string('priority')))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($inner) use ($request) {
                $term = '%'.$request->string('search').'%';
                $inner->where('tracking_code', 'like', $term)->orWhere('title', 'like', $term);
            }))
            ->latest()->paginate(12)->withQueryString();

        return view('requests.index', compact('requests'));
    }

    public function create(): View
    {
        return view('requests.create', [
            'types' => RequestType::where('active', true)->orderBy('name')->get(),
            'students' => User::whereHas('role', fn ($q) => $q->where('name', 'STUDENT'))->orderBy('name')->get(),
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $request->validate(['student_id' => ['required', 'exists:users,id']]);
        $student = User::with('role')->findOrFail($request->integer('student_id'));
        abort_unless($student->role?->name === 'STUDENT', 422, 'Selecciona un estudiante válido.');
        $created = $this->service->create($student, $request->validated(), $request->user());

        return redirect()->route('requests.show', $created)->with('success', 'Solicitud registrada.');
    }

    public function show(ServiceRequest $serviceRequest): View
    {
        $serviceRequest->load([
            'type', 'student', 'assignee', 'comments.user', 'evidences.user',
            'statusHistory.user', 'resourceAssignments.resource',
        ]);

        return view('requests.show', [
            'serviceRequest' => $serviceRequest,
            'admins' => User::whereHas('role', fn ($q) => $q->where('name', 'ADMIN'))->orderBy('name')->get(),
        ]);
    }

    public function edit(ServiceRequest $serviceRequest): View
    {
        return view('requests.edit', ['serviceRequest' => $serviceRequest, 'types' => RequestType::where('active', true)->get()]);
    }

    public function update(UpdateServiceRequest $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->service->update($serviceRequest, $request->validated());

        return redirect()->route('requests.show', $serviceRequest)->with('success', 'Solicitud actualizada.');
    }

    public function destroy(ServiceRequest $serviceRequest): RedirectResponse
    {
        $serviceRequest->delete();

        return redirect()->route('requests.index')->with('success', 'Solicitud eliminada.');
    }

    public function assign(AssignServiceRequest $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $assignee = User::with('role')->findOrFail($request->integer('assigned_to'));
        abort_unless($assignee->isAdmin(), 422, 'El responsable debe ser administrativo.');
        $this->service->assign($serviceRequest, $assignee, $request->user());

        return back()->with('success', 'Responsable asignado.');
    }

    public function changeStatus(ChangeStatusRequest $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->service->changeStatus($serviceRequest, $request->string('status')->toString(), $request->user(), $request->input('comment'));

        return back()->with('success', 'Estado actualizado.');
    }

    public function comment(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);
        $serviceRequest->comments()->create([...$data, 'user_id' => $request->user()->id]);

        return back()->with('success', 'Comentario publicado.');
    }

    public function evidence(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240']]);
        $file = $request->file('file');
        $serviceRequest->evidences()->create([
            'user_id' => $request->user()->id,
            'original_name' => $file->getClientOriginalName(),
            'path' => $file->store("evidences/{$serviceRequest->id}", 'public'),
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'Evidencia adjuntada.');
    }
}
