<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CommitteeVote;
use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * Display a listing of verified applications (status = 'verify').
     */
    public function index(Request $request)
    {
        $query = Application::query()
            ->whereIn('status', ['verify', 'under_review'])
            ->with(['user', 'verifier', 'votes']);

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

    /**
     * Show a single application with full details.
     */
    public function show($id)
    {
        $application = Application::with(['user', 'documents', 'reviewer', 'verifier', 'votes.committee'])
            ->findOrFail($id);

        // Only allow viewing verified applications or under review or approved/rejected ones
        if (!in_array($application->status, ['verify', 'under_review', 'approved', 'rejected'])) {
            abort(404);
        }

        // Get current committee member
        $currentCommittee = Auth::guard('committee')->user();
        
        // Check if current committee has already voted
        $hasVoted = $application->votes()
            ->where('committee_id', $currentCommittee->id)
            ->exists();

        // Get total committee count
        $totalCommittees = Committee::count();
        
        // Get vote counts
        $approveCount = $application->votes()->where('vote', 'approved')->count();
        $rejectCount = $application->votes()->where('vote', 'rejected')->count();
        $totalVotes = $application->votes()->count();

        return view('committee.application', [
            'application' => $application,
            'hasVoted' => $hasVoted,
            'currentVote' => $hasVoted ? $application->votes()->where('committee_id', $currentCommittee->id)->first() : null,
            'totalCommittees' => $totalCommittees,
            'approveCount' => $approveCount,
            'rejectCount' => $rejectCount,
            'totalVotes' => $totalVotes,
        ]);
    }

    /**
     * Approve an application (vote).
     */
    public function approve(Request $request, $id): RedirectResponse
    {
        $application = Application::whereIn('status', ['verify', 'under_review'])->findOrFail($id);
        $currentCommittee = Auth::guard('committee')->user();

        // Check if already voted
        $existingVote = CommitteeVote::where('application_id', $application->id)
            ->where('committee_id', $currentCommittee->id)
            ->first();

        if ($existingVote) {
            return redirect()
                ->route('committee.applications.show', $application->id)
                ->with('error', 'You have already voted on this application.');
        }

        $validated = $request->validate([
            'amount_approved' => 'required|numeric|min:0',
            'committee_remarks' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($application, $currentCommittee, $validated) {
            // Create vote
            CommitteeVote::create([
                'application_id' => $application->id,
                'committee_id' => $currentCommittee->id,
                'vote' => 'approved',
                'amount_approved' => $validated['amount_approved'],
                'remarks' => $validated['committee_remarks'] ?? null,
            ]);

            // Reload application to get fresh vote counts
            $application->refresh();
            $application->load('votes');

            // Update application with remarks if this is the first vote
            if ($application->committee_remarks === null && !empty($validated['committee_remarks'])) {
                $application->committee_remarks = $validated['committee_remarks'];
            }

            // Get vote counts (after creating the vote)
            $totalCommittees = Committee::count();
            $approveCount = $application->votes()->where('vote', 'approved')->count();
            $totalVotes = $application->votes()->count();

            // Calculate average amount approved
            $avgAmount = $application->votes()
                ->where('vote', 'approved')
                ->whereNotNull('amount_approved')
                ->avg('amount_approved');

            // Update status based on voting
            if ($approveCount >= 3) {
                // 3 out of 5 approved - set to approved
                $application->update([
                    'status' => 'approved',
                    'amount_approved' => $avgAmount,
                    'reviewed_at' => now(),
                ]);
            } elseif ($totalVotes < $totalCommittees) {
                // Not all have voted yet - set to under_review
                $application->update([
                    'status' => 'under_review',
                ]);
            } else {
                // All voted but less than 3 approved - set to rejected
                $application->update([
                    'status' => 'rejected',
                    'reviewed_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('committee.applications.show', $application->id)
            ->with('success', 'Your vote has been recorded.');
    }

    /**
     * Reject an application (vote).
     */
    public function reject(Request $request, $id): RedirectResponse
    {
        $application = Application::whereIn('status', ['verify', 'under_review'])->findOrFail($id);
        $currentCommittee = Auth::guard('committee')->user();

        // Check if already voted
        $existingVote = CommitteeVote::where('application_id', $application->id)
            ->where('committee_id', $currentCommittee->id)
            ->first();

        if ($existingVote) {
            return redirect()
                ->route('committee.applications.show', $application->id)
                ->with('error', 'You have already voted on this application.');
        }

        $validated = $request->validate([
            'committee_remarks' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($application, $currentCommittee, $validated) {
            // Create vote
            CommitteeVote::create([
                'application_id' => $application->id,
                'committee_id' => $currentCommittee->id,
                'vote' => 'rejected',
                'remarks' => $validated['committee_remarks'] ?? null,
            ]);

            // Reload application to get fresh vote counts
            $application->refresh();
            $application->load('votes');

            // Update application with remarks if this is the first vote
            if ($application->committee_remarks === null && !empty($validated['committee_remarks'])) {
                $application->committee_remarks = $validated['committee_remarks'];
            }

            // Get vote counts (after creating the vote)
            $totalCommittees = Committee::count();
            $approveCount = $application->votes()->where('vote', 'approved')->count();
            $totalVotes = $application->votes()->count();

            // Calculate average amount approved (if any approvals exist)
            $avgAmount = $application->votes()
                ->where('vote', 'approved')
                ->whereNotNull('amount_approved')
                ->avg('amount_approved');

            // Update status based on voting
            if ($approveCount >= 3) {
                // 3 out of 5 approved - set to approved
                $application->update([
                    'status' => 'approved',
                    'amount_approved' => $avgAmount,
                    'reviewed_at' => now(),
                ]);
            } elseif ($totalVotes < $totalCommittees) {
                // Not all have voted yet - set to under_review
                $application->update([
                    'status' => 'under_review',
                ]);
            } else {
                // All voted but less than 3 approved - set to rejected
                $application->update([
                    'status' => 'rejected',
                    'reviewed_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('committee.applications.show', $application->id)
            ->with('success', 'Your vote has been recorded.');
    }
}

