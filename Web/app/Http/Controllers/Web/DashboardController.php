<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $statusCounts = ServiceRequest::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $typeCounts = ServiceRequest::join('request_types', 'requests.request_type_id', '=', 'request_types.id')
            ->selectRaw('request_types.name as label, count(*) as total')->groupBy('request_types.id', 'request_types.name')
            ->orderByDesc('total')->pluck('total', 'label');
        $priorityCounts = ServiceRequest::selectRaw('priority as label, count(*) as total')->groupBy('priority')->pluck('total', 'label');

        return view('dashboard.index', [
            'statusCounts' => $statusCounts,
            'typeCounts' => $typeCounts,
            'priorityCounts' => $priorityCounts,
            'total' => ServiceRequest::count(),
            'recent' => ServiceRequest::with(['type', 'student', 'assignee'])->latest()->limit(6)->get(),
        ]);
    }
}
