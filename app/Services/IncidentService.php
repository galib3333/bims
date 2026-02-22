<?php

namespace App\Services;

use App\Models\Incident;

class IncidentService
{
    public function create(array $data): Incident
    {
        return Incident::create($data);
    }
    public function getForMap(array $filters = []): array
    {
        $query = \App\Models\Incident::query();

        // Apply filters
        if (!empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }
        if (!empty($filters['division'])) {
            $query->where('division', $filters['division']);
        }
        if (!empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['start_date'])) {
            $query->whereDate('occurred_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('occurred_at', '<=', $filters['end_date']);
        }

        // Sorting optional for map
        $query->orderBy('occurred_at', 'desc');

        // Transform to map-friendly array
        return $query->get()->map(function ($incident) {
            return [
                'lat' => $incident->latitude,
                'lng' => $incident->longitude,
                'severity' => $incident->severity,
                'title' => $incident->title,
                'category' => $incident->category,
                'occurred_at' => $incident->occurred_at->toDateTimeString()
            ];
        })->toArray();
    }
}
