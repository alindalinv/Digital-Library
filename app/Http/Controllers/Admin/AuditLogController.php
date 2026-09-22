<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:audit-logs.view');
    }

    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->toString();
        $action = $request->string('action')->trim()->toString();
        $dateFrom = $request->date('from');
        $dateTo = $request->date('to');

        $logs = AuditLog::with('user:id,name,email')
            ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('action', 'like', "%{$search}%")
                    ->orWhere('model_type', 'like', "%{$search}%")
                    ->orWhere('model_id', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($user) => $user
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            }))
            ->when($action, fn ($query) => $query->where('action', $action))
            ->when($dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $actions = AuditLog::query()->select('action')->distinct()->orderBy('action')->pluck('action');
        $viewData = compact('logs', 'actions', 'search', 'action', 'dateFrom', 'dateTo') + ['title' => 'Audit Logs'];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.audit-logs._results', compact('logs'))->render(),
                'pagination' => (string) $logs->links(),
                'total' => $logs->total(),
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'next_page_url' => $logs->nextPageUrl(),
                'prev_page_url' => $logs->previousPageUrl(),
            ]);
        }

        return view('admin.audit-logs.index', $viewData);
    }
}
