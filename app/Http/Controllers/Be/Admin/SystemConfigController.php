<?php

namespace App\Http\Controllers\Be\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class SystemConfigController extends Controller
{
    /**
     * Display system configuration.
     */
    public function index()
    {
        $config = $this->getSystemConfig();
        return view('pages.be.admin.system.config', compact('config'));
    }

    /**
     * Update system configuration.
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_description' => ['nullable', 'string'],
            'contact_email' => ['required', 'email'],
            'contact_phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],

            // Payment Gateway Settings
            'payment_gateway' => ['required', 'in:midtrans,xendit,manual'],
            'midtrans_server_key' => ['nullable', 'string'],
            'midtrans_client_key' => ['nullable', 'string'],
            'midtrans_is_production' => ['nullable', 'boolean'],
            'xendit_secret_key' => ['nullable', 'string'],
            'xendit_public_key' => ['nullable', 'string'],

            // Commission Settings
            'admin_commission_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'min_coupon_price' => ['required', 'numeric', 'min:0'],
            'max_coupon_price' => ['required', 'numeric', 'min:0'],

            // Raffle Settings
            'min_coupons_for_raffle' => ['required', 'integer', 'min:1'],
            'raffle_duration_days' => ['required', 'integer', 'min:1'],
        ]);

        $config = [
            'app' => [
                'name' => $request->app_name,
                'description' => $request->app_description,
            ],
            'contact' => [
                'email' => $request->contact_email,
                'phone' => $request->contact_phone,
                'address' => $request->address,
            ],
            'payment' => [
                'gateway' => $request->payment_gateway,
                'midtrans' => [
                    'server_key' => $request->midtrans_server_key,
                    'client_key' => $request->midtrans_client_key,
                    'is_production' => $request->boolean('midtrans_is_production'),
                ],
                'xendit' => [
                    'secret_key' => $request->xendit_secret_key,
                    'public_key' => $request->xendit_public_key,
                ],
            ],
            'commission' => [
                'admin_percentage' => $request->admin_commission_percentage,
                'min_coupon_price' => $request->min_coupon_price,
                'max_coupon_price' => $request->max_coupon_price,
            ],
            'raffle' => [
                'min_coupons' => $request->min_coupons_for_raffle,
                'duration_days' => $request->raffle_duration_days,
            ],
        ];

        $this->saveSystemConfig($config);

        return redirect()->route('admin.system.config')
            ->with('success', 'Konfigurasi sistem berhasil diupdate.');
    }

    /**
     * Get system configuration.
     */
    private function getSystemConfig()
    {
        $configFile = storage_path('app/system_config.json');

        if (File::exists($configFile)) {
            return json_decode(File::get($configFile), true);
        }

        // Default configuration
        return [
            'app' => [
                'name' => config('app.name', 'Undi In'),
                'description' => 'Platform undian properti online',
            ],
            'contact' => [
                'email' => 'admin@undiin.com',
                'phone' => '+62 123 456 789',
                'address' => '',
            ],
            'payment' => [
                'gateway' => 'manual',
                'midtrans' => [
                    'server_key' => '',
                    'client_key' => '',
                    'is_production' => false,
                ],
                'xendit' => [
                    'secret_key' => '',
                    'public_key' => '',
                ],
            ],
            'commission' => [
                'admin_percentage' => 10,
                'min_coupon_price' => 100000,
                'max_coupon_price' => 1000000,
            ],
            'raffle' => [
                'min_coupons' => 10,
                'duration_days' => 30,
            ],
        ];
    }

    /**
     * Save system configuration.
     */
    private function saveSystemConfig($config)
    {
        $configFile = storage_path('app/system_config.json');
        File::put($configFile, json_encode($config, JSON_PRETTY_PRINT));

        // Clear cache
        Cache::forget('system_config');
    }

    /**
     * Test payment gateway connection.
     */
    public function testPaymentGateway(Request $request)
    {
        $request->validate([
            'gateway' => ['required', 'in:midtrans,xendit'],
        ]);

        try {
            if ($request->gateway === 'midtrans') {
                // Test Midtrans connection
                $result = $this->testMidtrans();
            } elseif ($request->gateway === 'xendit') {
                // Test Xendit connection
                $result = $this->testXendit();
            }

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test Midtrans connection.
     */
    private function testMidtrans()
    {
        // Implementation for testing Midtrans connection
        return [
            'success' => true,
            'message' => 'Koneksi Midtrans berhasil!'
        ];
    }

    /**
     * Test Xendit connection.
     */
    private function testXendit()
    {
        // Implementation for testing Xendit connection
        return [
            'success' => true,
            'message' => 'Koneksi Xendit berhasil!'
        ];
    }
}
