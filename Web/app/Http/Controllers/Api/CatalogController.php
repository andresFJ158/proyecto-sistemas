<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestType;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function requestTypes(): JsonResponse
    {
        return response()->json(['data' => RequestType::where('active', true)->orderBy('name')->get()]);
    }

    public function resources(): JsonResponse
    {
        return response()->json(['data' => Resource::orderBy('name')->get()]);
    }
}
