<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $closed = ServiceRequest::whereNotNull('closed_at')->get();
        $averageHours = $closed->isEmpty() ? 0 : round($closed->avg(fn ($item) => $item->created_at->diffInMinutes($item->closed_at)) / 60, 1);

        return view('reports.index', [
            'averageHours' => $averageHours,
            'byAssignee' => ServiceRequest::with('assignee')->selectRaw('assigned_to, count(*) as total')
                ->whereNotNull('assigned_to')->groupBy('assigned_to')->orderByDesc('total')->get(),
            'oldestPending' => ServiceRequest::with(['student', 'type'])->whereIn('status', ['PENDIENTE', 'ASIGNADA', 'EN_PROCESO'])->oldest()->limit(10)->get(),
        ]);
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Código', 'Título', 'Tipo', 'Prioridad', 'Estado', 'Estudiante', 'Responsable', 'Creada', 'Cerrada']);
            ServiceRequest::with(['type', 'student', 'assignee'])->orderBy('id')->chunk(200, function ($requests) use ($output) {
                foreach ($requests as $request) {
                    fputcsv($output, [$request->tracking_code, $request->title, $request->type->name, $request->priority, $request->status, $request->student->name, $request->assignee?->name, $request->created_at, $request->closed_at]);
                }
            });
            fclose($output);
        }, 'reporte-solicitudes.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
