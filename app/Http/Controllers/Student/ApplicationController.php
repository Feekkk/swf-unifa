<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ApplicationRequest;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * Show the application form.
     */
    public function create()
    {
        return view('student.application');
    }

    /**
     * Store a new application.
     */
    public function store(ApplicationRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Prepare application data
            $applicationData = $this->prepareApplicationData($request);

            // Create the application
            $application = Application::create([
                'user_id' => $user->id,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'amount_applied' => $request->amount_applied ?? null,
                'application_data' => $applicationData,
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'status' => 'pending',
            ]);

            // Handle file uploads
            if ($request->hasFile('documents')) {
                $this->storeDocuments($application, $request->file('documents'));
            }

            DB::commit();

            Log::info('Application submitted successfully', [
                'user_id' => $user->id,
                'application_id' => $application->id,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Your application has been submitted successfully. You will be notified once it has been reviewed.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Application submission failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting your application. Please try again.');
        }
    }

    /**
     * Prepare application data from request.
     */
    private function prepareApplicationData(ApplicationRequest $request): array
    {
        $data = [];

        // Outpatient specific fields
        if ($request->category === 'illness' && $request->subcategory === 'outpatient') {
            $data['clinic_name'] = $request->clinic_name;
            $data['reason_visit'] = $request->reason_visit;
            $data['visit_date'] = $request->visit_date;
        }

        // Inpatient specific fields
        if ($request->category === 'illness' && $request->subcategory === 'inpatient') {
            $data['reason_visit'] = $request->reason_visit;
            $data['check_in_date'] = $request->check_in_date;
            $data['check_out_date'] = $request->check_out_date;
        }

        // Disaster and Others specific fields
        if ($request->category === 'emergency' && in_array($request->subcategory, ['disaster', 'others'])) {
            $data['case_description'] = $request->case_description;
        }

        return $data;
    }

    /**
     * Store uploaded documents.
     */
    private function storeDocuments(Application $application, array $files): void
    {
        foreach ($files as $file) {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'applications/' . $application->id . '/' . $filename;

            // Store file in storage/app/public/applications
            $file->storeAs('applications/' . $application->id, $filename, 'public');

            // Save document metadata
            ApplicationDocument::create([
                'application_id' => $application->id,
                'filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
    }

    /**
     * Add a document to an existing application (only if status is pending).
     */
    public function addDocument(Request $request, $id): RedirectResponse
    {
        $application = Application::where('user_id', Auth::id())
            ->findOrFail($id);

        // Check if application status is pending
        if ($application->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'You can only edit documents for pending applications.');
        }

        $request->validate([
            'document' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        try {
            $file = $request->file('document');
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'applications/' . $application->id . '/' . $filename;

            // Store file
            $file->storeAs('applications/' . $application->id, $filename, 'public');

            // Save document metadata
            ApplicationDocument::create([
                'application_id' => $application->id,
                'filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            Log::info('Document added to application', [
                'user_id' => Auth::id(),
                'application_id' => $application->id,
                'filename' => $file->getClientOriginalName(),
            ]);

            return redirect()->back()
                ->with('success', 'Document added successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to add document', [
                'user_id' => Auth::id(),
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to add document. Please try again.');
        }
    }

    /**
     * Delete a document from an application (only if status is pending).
     */
    public function deleteDocument($id, $documentId): RedirectResponse
    {
        $application = Application::where('user_id', Auth::id())
            ->findOrFail($id);

        // Check if application status is pending
        if ($application->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'You can only delete documents from pending applications.');
        }

        $document = ApplicationDocument::where('application_id', $application->id)
            ->where('id', $documentId)
            ->firstOrFail();

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Delete document record
            $document->delete();

            Log::info('Document deleted from application', [
                'user_id' => Auth::id(),
                'application_id' => $application->id,
                'document_id' => $documentId,
            ]);

            return redirect()->back()
                ->with('success', 'Document deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to delete document', [
                'user_id' => Auth::id(),
                'application_id' => $application->id,
                'document_id' => $documentId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete document. Please try again.');
        }
    }
}
