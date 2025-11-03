<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the admin profile edit form.
     */
    public function edit()
    {
        return view('admin.editProfile');
    }

    /**
     * Update the admin profile.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $validated = $request->validated();

        // Update basic information
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        // Update password if provided
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.dashboard')->with('success', 'Profile updated successfully.');
    }
}