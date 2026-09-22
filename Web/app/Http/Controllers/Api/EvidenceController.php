<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EvidenceController extends Controller
{
    public function index(ServiceRequest $serviceRequest): JsonResponse
    {
        Gate::authorize('view', $serviceRequest);

        return response()->json(['data' => $serviceRequest->evidences()->with('user:id,name')->latest()->get()]);
    }

    public function store(Request $request, ServiceRequest $serviceRequest): JsonResponse
    {
        Gate::authorize('view', $serviceRequest);
        $request->validate(['file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240']]);
        $file = $request->file('file');
        $path = $file->store("evidences/{$serviceRequest->id}", 'public');

        $evidence = $serviceRequest->evidences()->create([
            'user_id' => $request->user()->id,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'size' => $file->getSize(),
        ]);

        return response()->json(['message' => 'Evidencia adjuntada.', 'data' => $evidence], 201);
    }
}
