<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function index(ServiceRequest $serviceRequest): JsonResponse
    {
        Gate::authorize('view', $serviceRequest);

        return response()->json(['data' => $serviceRequest->comments()->with('user:id,name')->oldest()->get()]);
    }

    public function store(Request $request, ServiceRequest $serviceRequest): JsonResponse
    {
        Gate::authorize('view', $serviceRequest);
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);
        $comment = $serviceRequest->comments()->create([...$data, 'user_id' => $request->user()->id]);

        return response()->json(['message' => 'Comentario publicado.', 'data' => $comment->load('user:id,name')], 201);
    }
}
