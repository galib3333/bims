<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IncidentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IncidentController extends Controller
{
    protected IncidentService $incidentService;

    public function __construct(IncidentService $incidentService)
    {
        $this->incidentService = $incidentService;
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'severity' => 'required|integer|min:1|max:5',
            'category' => 'required|string|max:100',
            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'occurred_at' => 'nullable|date'
        ]);

        $incident = $this->incidentService->create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $incident
        ], 201);
    }
    public function index(Request $request): JsonResponse
    {
        $query = \App\Models\Incident::query();

        // Filters
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->has('division')) {
            $query->where('division', $request->division);
        }
        if ($request->has('district')) {
            $query->where('district', $request->district);
        }
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('start_date')) {
            $query->whereDate('occurred_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('occurred_at', '<=', $request->end_date);
        }

        // Sorting
        $sort = $request->get('sort', 'latest'); // default latest
        if ($sort === 'oldest') {
            $query->orderBy('occurred_at', 'asc');
        } elseif ($sort === 'severity_high') {
            $query->orderBy('severity', 'desc');
        } elseif ($sort === 'severity_low') {
            $query->orderBy('severity', 'asc');
        } else {
            $query->orderBy('occurred_at', 'desc'); // latest
        }

        $incidents = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $incidents
        ]);
    }

    public function map(Request $request): JsonResponse
    {
        $filters = $request->only([
            'severity',
            'division',
            'district',
            'category',
            'start_date',
            'end_date'
        ]);

        $data = $this->incidentService->getForMap($filters);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
