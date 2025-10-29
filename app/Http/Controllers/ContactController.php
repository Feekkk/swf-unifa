<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'student_id' => 'nullable|string|regex:/^[0-9]{12}$/',
            'message' => 'required|string|min:10|max:1000',
        ], [
            'name.required' => 'Full name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name cannot exceed 100 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'student_id.regex' => 'Student ID must be exactly 12 digits.',
            'message.required' => 'Message is required.',
            'message.min' => 'Message must be at least 10 characters.',
            'message.max' => 'Message cannot exceed 1000 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors below and try again.');
        }

        try {
            // Get validated data
            $validatedData = $validator->validated();
            
            // Sanitize the data
            $contactData = [
                'name' => strip_tags(trim($validatedData['name'])),
                'email' => filter_var($validatedData['email'], FILTER_SANITIZE_EMAIL),
                'student_id' => $validatedData['student_id'] ? strip_tags(trim($validatedData['student_id'])) : null,
                'message' => strip_tags(trim($validatedData['message'])),
                'submitted_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            // Log the contact form submission for record keeping
            Log::info('Contact form submission', [
                'name' => $contactData['name'],
                'email' => $contactData['email'],
                'student_id' => $contactData['student_id'],
                'ip_address' => $contactData['ip_address'],
                'submitted_at' => $contactData['submitted_at'],
            ]);

            // Send email notification (you can customize this based on your email setup)
            $this->sendContactNotification($contactData);

            // Return success response
            return redirect()->back()
                ->with('success', 'Thank you for your message! We have received your inquiry and will respond within 1-2 business days.')
                ->withInput([]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'name' => $request->name,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'We apologize, but there was an error sending your message. Please try again or contact us directly at sw.rcmp@unikl.edu.my.');
        }
    }

    /**
     * Send contact form notification email
     */
    private function sendContactNotification($contactData)
    {
        try {
            // Email configuration - you can customize this
            $toEmail = env('CONTACT_EMAIL', 'sw.rcmp@unikl.edu.my');
            $subject = 'New Contact Form Submission - UniKL RCMP SWF';
            
            // Create email content
            $emailContent = $this->buildEmailContent($contactData);
            
            // Send email using Laravel's Mail facade
            Mail::raw($emailContent, function ($message) use ($toEmail, $subject, $contactData) {
                $message->to($toEmail)
                    ->subject($subject)
                    ->replyTo($contactData['email'], $contactData['name']);
            });

            // Send auto-reply to the user
            $this->sendAutoReply($contactData);

        } catch (\Exception $e) {
            // Log email sending error but don't fail the form submission
            Log::warning('Failed to send contact form email notification', [
                'error' => $e->getMessage(),
                'contact_data' => $contactData,
            ]);
        }
    }

    /**
     * Send auto-reply to the user
     */
    private function sendAutoReply($contactData)
    {
        try {
            $subject = 'Thank you for contacting UniKL RCMP Student Welfare Fund';
            $autoReplyContent = $this->buildAutoReplyContent($contactData);

            Mail::raw($autoReplyContent, function ($message) use ($contactData, $subject) {
                $message->to($contactData['email'], $contactData['name'])
                    ->subject($subject)
                    ->from(env('MAIL_FROM_ADDRESS', 'noreply@unikl.edu.my'), 'UniKL RCMP Student Welfare Fund');
            });

        } catch (\Exception $e) {
            Log::warning('Failed to send auto-reply email', [
                'error' => $e->getMessage(),
                'email' => $contactData['email'],
            ]);
        }
    }

    /**
     * Build email content for staff notification
     */
    private function buildEmailContent($contactData)
    {
        $content = "New Contact Form Submission\n";
        $content .= "================================\n\n";
        $content .= "Name: " . $contactData['name'] . "\n";
        $content .= "Email: " . $contactData['email'] . "\n";
        
        if ($contactData['student_id']) {
            $content .= "Student ID: " . $contactData['student_id'] . "\n";
        }
        
        $content .= "Submitted: " . $contactData['submitted_at']->format('Y-m-d H:i:s') . "\n";
        $content .= "IP Address: " . $contactData['ip_address'] . "\n\n";
        $content .= "Message:\n";
        $content .= "--------\n";
        $content .= $contactData['message'] . "\n\n";
        $content .= "================================\n";
        $content .= "This message was sent via the UniKL RCMP SWF website contact form.\n";
        $content .= "Please respond directly to the sender's email address.";

        return $content;
    }

    /**
     * Build auto-reply content for the user
     */
    private function buildAutoReplyContent($contactData)
    {
        $content = "Dear " . $contactData['name'] . ",\n\n";
        $content .= "Thank you for contacting the UniKL RCMP Student Welfare Fund. We have received your message and will respond within 1-2 business days.\n\n";
        $content .= "Your message:\n";
        $content .= "\"" . $contactData['message'] . "\"\n\n";
        $content .= "If you have an urgent matter, please contact us directly:\n";
        $content .= "Email: sw.rcmp@unikl.edu.my\n";
        $content .= "Phone: +60 5-253 6200\n\n";
        $content .= "Office Hours:\n";
        $content .= "Monday - Friday: 8:00 AM - 5:00 PM\n";
        $content .= "Saturday: 8:00 AM - 12:00 PM\n";
        $content .= "Sunday: Closed\n\n";
        $content .= "Best regards,\n";
        $content .= "UniKL RCMP Student Welfare Fund Team\n";
        $content .= "Campus Lifestyle Section\n";
        $content .= "Universiti Kuala Lumpur Royal College of Medicine Perak";

        return $content;
    }
}