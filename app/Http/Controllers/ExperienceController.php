<?php

namespace App\Http\Controllers;

use App\Models\Experience;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('period_from', 'desc')
            ->get();

        return response()->json([
            'experiences' => $experiences->map(function ($experience) {
                return [
                    'id' => $experience->id,
                    'role' => $experience->role,
                    'company' => $experience->company,
                    'location' => $experience->location,
                    'period' => $experience->period, // Uses accessor
                    'period_from' => $experience->period_from,
                    'period_to' => $experience->period_to,
                    'is_current' => $experience->is_current,
                    'description' => $experience->description,
                ];
            })
        ]);
    }
}