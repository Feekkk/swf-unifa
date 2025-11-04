<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of verified applications (status = 'verify').
     */
    public function index(Request $request)
    {
        $query = Application::query()
            ->where('status', 'verify')
            ->with(['user', 'verifier']);

        // Search by application id, user name, user student_id
        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($search) {
                // Handle APP- format search (e.g., "APP-000001" or "APP-1" or just "1")
                $numericSearch = preg_replace('/[^0-9]/', '', $search);
                if ($numericSearch && is_numeric($numericSearch)) {
                    $q->where('id', '=', (int) $numericSearch);
                }
                
                // Search by subcategory
                $q->orWhere('subcategory', 'like', "%$search%")
                  // Search by user full_name, username, student_id, or email
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where(function ($userQuery) use ($search) {
                          $userQuery->where('full_name', 'like', "%$search%")
                                    ->orWhere('username', 'like', "%$search%")
                                    ->orWhere('student_id', 'like', "%$search%")
                                    ->orWhere('email', 'like', "%$search%");
                      });
                  });
            });
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

        return view('committee.viewApplication', [
            'applications' => $applications,
            'filters' => [
                'q' => (string) $request->input('q', ''),
                'category' => (string) $request->input('category', 'all'),
                'sort' => (string) $request->input('sort', 'newest'),
            ]
        ]);
    }
}

