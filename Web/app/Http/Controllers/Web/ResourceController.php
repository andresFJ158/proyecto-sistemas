<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(): View
    {
        return view('resources.index', ['resources' => Resource::latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('resources.create');
    }

    public function edit(Resource $resource): View
    {
        return view('resources.edit', compact('resource'));
    }

    public function store(Request $request): RedirectResponse
    {
        Resource::create($this->validated($request));

        return redirect()->route('resources.index')->with('success', 'Recurso creado.');
    }

    public function update(Request $request, Resource $resource): RedirectResponse
    {
        $resource->update($this->validated($request, $resource));

        return redirect()->route('resources.index')->with('success', 'Recurso actualizado.');
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        $resource->delete();

        return back()->with('success', 'Recurso eliminado.');
    }

    private function validated(Request $request, ?Resource $resource = null): array
    {
        return $request->validate([
            'code' => ['required', 'max:50', Rule::unique('resources')->ignore($resource)],
            'name' => ['required', 'max:160'],
            'category' => ['required', 'max:120'],
            'status' => ['required', Rule::in(Resource::STATUSES)],
            'location' => ['nullable', 'max:180'],
            'description' => ['nullable', 'max:2000'],
        ]);
    }
}
