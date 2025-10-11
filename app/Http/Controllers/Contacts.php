<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class Contacts extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactInfo = Contact::first();

        return response()->json(
            [
                'email' => $contactInfo->email,
                'phone' => $contactInfo->phone,
                'location' => $contactInfo->location,
                'twitter' => $contactInfo->twitter,
                'linkedin' => $contactInfo->linkedin,
                'github' => $contactInfo->github,
                'facebook' => $contactInfo->facebook,
                'instagram' => $contactInfo->instagram,
                'youtube' => $contactInfo->youtube
            ]
        );
    }
}
