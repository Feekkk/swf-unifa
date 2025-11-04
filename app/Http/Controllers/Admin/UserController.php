<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserPasswordRequest;
use App\Models\User;
use App\Models\Admin;
use App\Models\Committee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role_filter');

        // Start with all users query
        $usersQuery = User::query();
        
        // Apply search filter
        if ($search) {
            $usersQuery->where(function($query) use ($search) {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Get all students
        $students = $usersQuery->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->full_name ?? $user->username,
                'email' => $user->email,
                'role' => 'student',
                'updated_at' => $user->updated_at,
            ];
        });

        // Get all admins
        $adminsQuery = Admin::query();
        
        if ($search) {
            $adminsQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $adminUsers = $adminsQuery->get()->map(function($admin) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'admin',
                'updated_at' => $admin->updated_at,
            ];
        });

        // Get all committees
        $committeesQuery = Committee::query();
        
        if ($search) {
            $committeesQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $committeeUsers = $committeesQuery->get()->map(function($committee) {
            return [
                'id' => $committee->id,
                'name' => $committee->name,
                'email' => $committee->email,
                'role' => 'committee',
                'updated_at' => $committee->updated_at,
            ];
        });

        // Combine all users
        $allUsers = $students->merge($adminUsers)->merge($committeeUsers);

        // Apply role filter if selected
        if ($roleFilter && in_array($roleFilter, ['student', 'admin', 'committee'])) {
            $allUsers = $allUsers->filter(function($user) use ($roleFilter) {
                return $user['role'] === $roleFilter;
            });
        }

        // Sort by updated_at descending
        $allUsers = $allUsers->sortByDesc('updated_at')->values();

        // Check if search was performed but no results found
        $noResults = $search && $allUsers->isEmpty();

        return view('admin.manageUser', [
            'users' => $allUsers,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'noResults' => $noResults,
        ]);
    }

    /**
     * Show the form for editing user password.
     */
    public function edit(Request $request, string $role, int $id): View
    {
        if ($role === 'student') {
            $user = User::findOrFail($id);
            $userName = $user->full_name ?? $user->username;
        } elseif ($role === 'admin') {
            $user = Admin::findOrFail($id);
            $userName = $user->name;
        } elseif ($role === 'committee') {
            $user = Committee::findOrFail($id);
            $userName = $user->name;
        } else {
            abort(404);
        }

        return view('admin.editUser', [
            'user' => $user,
            'role' => $role,
            'userName' => $userName,
        ]);
    }

    /**
     * Update the user password.
     */
    public function updatePassword(UpdateUserPasswordRequest $request, string $role, int $id): RedirectResponse
    {
        if ($role === 'student') {
            $user = User::findOrFail($id);
        } elseif ($role === 'admin') {
            $user = Admin::findOrFail($id);
        } elseif ($role === 'committee') {
            $user = Committee::findOrFail($id);
        } else {
            abort(404);
        }

        $user->password = Hash::make($request->validated()['password']);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User password updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(Request $request, string $role, int $id): RedirectResponse
    {
        if ($role === 'student') {
            $user = User::findOrFail($id);
            $userName = $user->full_name ?? $user->username;
        } elseif ($role === 'admin') {
            $user = Admin::findOrFail($id);
            $userName = $user->name;
        } elseif ($role === 'committee') {
            $user = Committee::findOrFail($id);
            $userName = $user->name;
        } else {
            abort(404);
        }

        // Prevent deleting the currently logged-in admin
        $loggedInAdmin = auth()->guard('admin')->user();
        if ($role === 'admin' && $loggedInAdmin && $loggedInAdmin->id === $id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$userName} has been removed successfully.");
    }
}

