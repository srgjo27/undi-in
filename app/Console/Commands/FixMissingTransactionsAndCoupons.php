<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Coupon;

class FixMissingTransactionsAndCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:missing-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing transactions and coupons for paid orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix missing transactions and coupons...');

        $paidOrders = Order::where('status', 'paid')
            ->whereDoesntHave('transactions')
            ->with('property')
            ->get();

        $this->info("Found {$paidOrders->count()} paid orders without transactions.");

        foreach ($paidOrders as $order) {
            $this->info("Processing Order ID: {$order->id}");

            try {
                Transaction::create([
                    'order_id' => $order->id,
                    'amount' => $order->total_price,
                    'payment_method' => 'bank_transfer',
                    'status' => 'completed',
                    'gateway_response' => [
                        'verified_by' => $order->verified_by,
                        'verified_at' => $order->verified_at,
                        'transfer_proof' => $order->transfer_proof,
                    ],
                ]);

                $this->info("✓ Transaction created for Order ID: {$order->id}");

                $existingCoupons = Coupon::where('order_id', $order->id)->count();
                $neededCoupons = $order->quantity - $existingCoupons;

                for ($i = 0; $i < $neededCoupons; $i++) {
                    $couponNumber = $this->generateCouponNumber($order->property_id);

                    Coupon::create([
                        'order_id' => $order->id,
                        'buyer_id' => $order->buyer_id,
                        'property_id' => $order->property_id,
                        'coupon_number' => $couponNumber,
                        'is_winner' => false,
                    ]);
                }

                $this->info("✓ {$neededCoupons} coupons created for Order ID: {$order->id}");
            } catch (\Exception $e) {
                $this->error("Failed to process Order ID: {$order->id} - " . $e->getMessage());
            }
        }

        $this->info('Fix completed!');
    }

    /**
     * Generate unique coupon number
     */
    protected function generateCouponNumber($propertyId)
    {
        do {
            $couponNumber = 'CPN-' . $propertyId . '-' . strtoupper(uniqid());
        } while (Coupon::where('coupon_number', $couponNumber)->exists());

        return $couponNumber;
    }
}
