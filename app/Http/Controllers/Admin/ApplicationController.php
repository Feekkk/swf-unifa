<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::query()->with(['user']);

        // Search by application id, user name, user student_id
        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('subcategory', 'like', "%$search%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('display_name', 'like', "%$search%")
                         ->orWhere('student_id', 'like', "%$search%");
                  });
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Category filter
        if ($category = $request->input('category')) {
            if ($category !== 'all') {
                $query->where('category', $category);
            }
        }

        // Sort
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'amount_desc') {
            $query->orderBy('amount_applied', 'desc');
        } elseif ($sort === 'amount_asc') {
            $query->orderBy('amount_applied', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $applications = $query->paginate(10)->withQueryString();

        return view('admin.viewApplication', [
            'applications' => $applications,
            'filters' => [
                'q' => (string) $request->input('q', ''),
                'status' => (string) $request->input('status', 'all'),
                'category' => (string) $request->input('category', 'all'),
                'sort' => (string) $request->input('sort', 'newest'),
            ]
        ]);
    }

    /**
     * Show a single application with full details.
     */
    public function show($id)
    {
        $application = Application::with(['user', 'documents', 'reviewer', 'verifier'])
            ->findOrFail($id);

        return view('admin.application', [
            'application' => $application,
        ]);
    }

    /**
     * Verify an application (sets status to verify).
     */
    public function verify(Request $request, $id): RedirectResponse
    {
        $application = Application::findOrFail($id);

        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status' => 'verify',
            'admin_notes' => $request->input('admin_notes'),
            'verified_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.applications.show', $application->id)
            ->with('success', 'Application has been verified successfully.');
    }
}


