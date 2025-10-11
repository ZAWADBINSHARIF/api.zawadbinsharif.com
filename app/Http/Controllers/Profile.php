<?php

namespace App\Http\Controllers;

use App\Models\Profile as ModelsProfile;

class Profile extends Controller
{
    public function index()
    {
        $profileInfo = ModelsProfile::first();

        if (!$profileInfo) {
            return response()->json([
                'profile' => null,
                'message' => 'No profile found'
            ], 404);
        }

        return response()->json([
            'profile' => [
                'name' => $profileInfo->full_name,
                'title' => $profileInfo->short_title,
                'short_description' => $profileInfo->introduction, // This contains HTML
                'about_me' => $profileInfo->about_me,
                'image' => $profileInfo->image ? asset('storage/' . $profileInfo->image) : null,
                'resume' => $profileInfo->resume ? asset('storage/' . $profileInfo->resume) : null,
                'github' => $profileInfo->github,
                'linkedin' => $profileInfo->linkedin,
                'twitter' => $profileInfo->twitter,
                'email' => $profileInfo->email,
                'phone' => $profileInfo->phone,
                'location' => $profileInfo->location,
                'availability' => $profileInfo->availability,
                'worked_technologies' => $profileInfo->worked_technologies
            ]
        ]);
    }
}
