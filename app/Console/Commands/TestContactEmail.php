<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class TestContactEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:contact-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test contact form email functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing contact form email...');

        $testData = [
            'name' => 'Test User',
            'email' => 'test.user@example.com',
            'subject' => 'account_blocked',
            'message' => 'This is a test message to verify that the contact form email functionality is working correctly.'
        ];

        try {
            $adminEmail = config('mail.admin_email');
            $this->info("Sending test email to: {$adminEmail}");

            Mail::to($adminEmail)->send(new ContactFormMail($testData));

            $this->info('Test email sent successfully!');
            $this->line('Check your email inbox for the test message.');
        } catch (\Exception $e) {
            $this->error('Failed to send test email');
            $this->error('Error: ' . $e->getMessage());

            if (config('mail.default') === 'log') {
                $this->warn('Note: Mail driver is set to "log". Check storage/logs/laravel.log for the email content.');
            }
        }

        $this->newLine();
        $this->line('Mail Configuration:');
        $this->line('- Driver: ' . config('mail.default'));
        $this->line('- Admin Email: ' . config('mail.admin_email'));
        $this->line('- From Address: ' . config('mail.from.address'));
        $this->line('- From Name: ' . config('mail.from.name'));

        return 0;
    }
}
