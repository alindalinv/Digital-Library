<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the admin profile page.
     */
    public function index(Request $request): View
    {
        return view('admin.pages.profile.index', [
            'title' => 'Edit Profile',
            'user' => $request->user(),
        ]);
    }
}