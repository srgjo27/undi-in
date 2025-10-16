<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Support\Str;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buyers = User::where('role', 'buyer')->get();

        if ($buyers->count() === 0) {
            $buyers = collect();
        }

        $properties = Property::where('status', 'active')->get();

        if ($properties->count() === 0) {
            $seller = User::where('role', 'seller')->first();

            $properties = collect();
            $propertyData = [
                ['title' => 'Villa Bali Indah', 'city' => 'Denpasar', 'province' => 'Bali', 'price' => 1000000000, 'coupon_price' => 50000],
                ['title' => 'Rumah Jakarta Selatan', 'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'price' => 800000000, 'coupon_price' => 35000],
                ['title' => 'Apartemen Bandung', 'city' => 'Bandung', 'province' => 'Jawa Barat', 'price' => 600000000, 'coupon_price' => 25000],
            ];

            foreach ($propertyData as $data) {
                $property = Property::create([
                    'seller_id' => $seller->id,
                    'title' => $data['title'],
                    'description' => 'Deskripsi properti ' . $data['title'],
                    'price' => $data['price'],
                    'coupon_price' => $data['coupon_price'],
                    'address' => 'Alamat ' . $data['title'],
                    'city' => $data['city'],
                    'province' => $data['province'],
                    'land_area' => 200,
                    'building_area' => 150,
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'sale_start_date' => now(),
                    'sale_end_date' => now()->addMonths(3),
                    'status' => 'active',
                ]);

                $properties->push($property);
            }
        }

        foreach ($properties->take(3) as $property) {
            $orderCount = rand(3, 8);

            for ($i = 0; $i < $orderCount; $i++) {
                $buyer = $buyers->random();
                $quantity = rand(1, 5);
                $totalPrice = $property->coupon_price * $quantity;

                $order = Order::create([
                    'buyer_id' => $buyer->id,
                    'property_id' => $property->id,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'status' => 'paid',
                    'payment_gateway' => 'bank_transfer',
                    'paid_at' => now(),
                ]);

                for ($j = 0; $j < $quantity; $j++) {
                    $couponNumber = $property->id . sprintf('%04d', $property->coupons()->count() + 1);

                    Coupon::create([
                        'order_id' => $order->id,
                        'buyer_id' => $buyer->id,
                        'property_id' => $property->id,
                        'coupon_number' => $couponNumber,
                        'is_winner' => false,
                    ]);
                }
            }
        }

        echo "Coupon seeder completed!\n";
        echo "Created orders and coupons for " . $properties->count() . " properties\n";
        echo "Total coupons created: " . Coupon::count() . "\n";
    }
}
