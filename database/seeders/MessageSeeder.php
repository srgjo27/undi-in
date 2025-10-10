<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for testing
        $admin = User::where('role', 'admin')->first();
        $seller = User::where('role', 'seller')->first();
        $buyer = User::where('role', 'buyer')->first();

        if (!$admin || !$seller || !$buyer) {
            $this->command->info('Please run other seeders first to create users');
            return;
        }

        // Create conversation between admin and seller
        $conversation1 = Conversation::createPrivateConversation($admin->id, $seller->id);

        // Add some messages
        Message::create([
            'conversation_id' => $conversation1->id,
            'sender_id' => $admin->id,
            'content' => 'Hello! I need to discuss your property listing.',
            'type' => 'text'
        ]);

        Message::create([
            'conversation_id' => $conversation1->id,
            'sender_id' => $seller->id,
            'content' => 'Hello Admin! Sure, what would you like to discuss?',
            'type' => 'text'
        ]);

        Message::create([
            'conversation_id' => $conversation1->id,
            'sender_id' => $admin->id,
            'content' => 'Your latest property submission needs some documentation updates.',
            'type' => 'text'
        ]);

        // Create conversation between seller and buyer
        $conversation2 = Conversation::createPrivateConversation($seller->id, $buyer->id);

        Message::create([
            'conversation_id' => $conversation2->id,
            'sender_id' => $buyer->id,
            'content' => 'Hi! I\'m interested in your property in Bali. Can you provide more details?',
            'type' => 'text'
        ]);

        Message::create([
            'conversation_id' => $conversation2->id,
            'sender_id' => $seller->id,
            'content' => 'Hello! Thank you for your interest. The property is located in a prime area with beautiful ocean views.',
            'type' => 'text'
        ]);

        Message::create([
            'conversation_id' => $conversation2->id,
            'sender_id' => $buyer->id,
            'content' => 'That sounds great! What\'s the price per coupon and how many coupons are available?',
            'type' => 'text'
        ]);

        Message::create([
            'conversation_id' => $conversation2->id,
            'sender_id' => $seller->id,
            'content' => 'Each coupon is priced at Rp 50,000 and we have 100 coupons available. The raffle will be conducted once all coupons are sold.',
            'type' => 'text'
        ]);

        // Create conversation between admin and buyer
        $conversation3 = Conversation::createPrivateConversation($admin->id, $buyer->id);

        Message::create([
            'conversation_id' => $conversation3->id,
            'sender_id' => $buyer->id,
            'content' => 'Hi Admin, I have a question about the raffle process.',
            'type' => 'text'
        ]);

        Message::create([
            'conversation_id' => $conversation3->id,
            'sender_id' => $admin->id,
            'content' => 'Hello! I\'m happy to help. What would you like to know about the raffle process?',
            'type' => 'text'
        ]);

        // Update last activity for conversations
        $conversation1->updateLastActivity();
        $conversation2->updateLastActivity();
        $conversation3->updateLastActivity();

        $this->command->info('Message seeder completed successfully!');
    }
}
