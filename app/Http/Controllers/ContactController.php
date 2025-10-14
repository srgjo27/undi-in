<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Handle contact form submission from blocked users or general inquiries
     */
    public function submitContactForm(Request $request)
    {
        $key = 'contact-form:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many attempts. Please try again in {$seconds} seconds."
            ], 429);
        }

        RateLimiter::hit($key, 300);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ]);

        try {
            Log::info('Contact form submission', [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $this->sendContactEmail($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully! Admin will respond within 24-48 hours.'
            ]);
        } catch (\Exception $e) {
            Log::error('Contact form error', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again or use alternative contact methods.'
            ], 500);
        }
    }

    /**
     * Send contact email to admin
     */
    private function sendContactEmail($data)
    {
        $adminEmail = config('mail.admin_email', 'operatorserbabisa123@gmail.com');

        try {
            Mail::to($adminEmail)->send(new ContactFormMail($data));

            Log::info('Contact email sent to admin', [
                'to' => $adminEmail,
                'from' => $data['email'],
                'subject' => $data['subject'],
                'name' => $data['name']
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send contact email', [
                'error' => $e->getMessage(),
                'to' => $adminEmail,
                'from' => $data['email']
            ]);

            Log::info('Contact form submission (email failed)', [
                'to' => $adminEmail,
                'from' => $data['email'],
                'subject' => $data['subject'],
                'name' => $data['name'],
                'message' => $data['message']
            ]);
        }
    }
}
