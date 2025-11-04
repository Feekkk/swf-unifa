<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Committee\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the committee profile edit form.
     */
    public function edit()
    {
        return view('committee.editProfile');
    }

    /**
     * Update the committee profile.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $committee = Auth::guard('committee')->user();
        $validated = $request->validated();

        // Update basic information
        $committee->name = $validated['name'];
        $committee->email = $validated['email'];

        // Update password if provided
        if (!empty($validated['password'])) {
            $committee->password = Hash::make($validated['password']);
        }

        $committee->save();

        return redirect()->route('committee.dashboard')->with('success', 'Profile updated successfully.');
    }
}

