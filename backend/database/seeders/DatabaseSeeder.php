<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Asset;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        User::truncate();
        Asset::truncate();
        Order::truncate();

        // ========== USER 1: Buyer-focused User ==========
        $user1 = User::create([
            'name' => 'Alice Trader',
            'email' => 'alice@example.com',
            'password' => Hash::make('password123'),
            'balance' => 150000.00 // $150,000
        ]);

        // Alice's assets
        Asset::create([
            'user_id' => $user1->id,
            'symbol' => 'BTC',
            'amount' => 3.5,
            'locked_amount' => 0
        ]);
        
        Asset::create([
            'user_id' => $user1->id,
            'symbol' => 'ETH',
            'amount' => 15.0,
            'locked_amount' => 0
        ]);

        // Alice's active BUY orders (wants to buy more)
        Order::create([
            'user_id' => $user1->id,
            'symbol' => 'BTC',
            'side' => 'buy',
            'price' => 94000.00,
            'amount' => 0.5,
            'filled_amount' => 0,
            'status' => 'open',
            'created_at' => now()->subHours(2)
        ]);

        Order::create([
            'user_id' => $user1->id,
            'symbol' => 'ETH',
            'side' => 'buy',
            'price' => 2900.00,
            'amount' => 5.0,
            'filled_amount' => 0,
            'status' => 'open',
            'created_at' => now()->subHours(1)
        ]);

        // ========== USER 2: Seller-focused User ==========
        $user2 = User::create([
            'name' => 'Bob Seller',
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
            'balance' => 75000.00 // $75,000
        ]);

        // Bob's assets (has more crypto to sell)
        Asset::create([
            'user_id' => $user2->id,
            'symbol' => 'BTC',
            'amount' => 8.0,
            'locked_amount' => 1.2 // Some locked in orders
        ]);
        
        Asset::create([
            'user_id' => $user2->id,
            'symbol' => 'ETH',
            'amount' => 30.0,
            'locked_amount' => 10.0 // Some locked in orders
        ]);

        // Bob's active SELL orders (wants to sell)
        Order::create([
            'user_id' => $user2->id,
            'symbol' => 'BTC',
            'side' => 'sell',
            'price' => 96000.00,
            'amount' => 1.2,
            'filled_amount' => 0,
            'status' => 'open',
            'created_at' => now()->subHours(3)
        ]);

        Order::create([
            'user_id' => $user2->id,
            'symbol' => 'ETH',
            'side' => 'sell',
            'price' => 3100.00,
            'amount' => 10.0,
            'filled_amount' => 0,
            'status' => 'open',
            'created_at' => now()->subHours(2)
        ]);

        // ========== USER 3: Balanced User ==========
        $user3 = User::create([
            'name' => 'Charlie Balanced',
            'email' => 'charlie@example.com',
            'password' => Hash::make('password123'),
            'balance' => 200000.00 // $200,000
        ]);

        // Charlie's assets
        Asset::create([
            'user_id' => $user3->id,
            'symbol' => 'BTC',
            'amount' => 5.0,
            'locked_amount' => 0.8
        ]);
        
        Asset::create([
            'user_id' => $user3->id,
            'symbol' => 'ETH',
            'amount' => 25.0,
            'locked_amount' => 5.0
        ]);

        // Charlie has both BUY and SELL orders
        Order::create([
            'user_id' => $user3->id,
            'symbol' => 'BTC',
            'side' => 'buy',
            'price' => 93000.00, // Lower buy price
            'amount' => 0.8,
            'filled_amount' => 0,
            'status' => 'open',
            'created_at' => now()->subHours(4)
        ]);

        Order::create([
            'user_id' => $user3->id,
            'symbol' => 'BTC',
            'side' => 'sell',
            'price' => 97000.00, // Higher sell price
            'amount' => 0.5,
            'filled_amount' => 0,
            'status' => 'open',
            'created_at' => now()->subHours(1)
        ]);

        // ========== User 4: Test User for Login ==========
        $user4 = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'balance' => 100000.00 // $100,000
        ]);

        Asset::create([
            'user_id' => $user4->id,
            'symbol' => 'BTC',
            'amount' => 5.0,
            'locked_amount' => 0
        ]);
        
        Asset::create([
            'user_id' => $user4->id,
            'symbol' => 'ETH',
            'amount' => 20.0,
            'locked_amount' => 0
        ]);

        // Test user has one open order
        Order::create([
            'user_id' => $user4->id,
            'symbol' => 'BTC',
            'side' => 'buy',
            'price' => 95000.00,
            'amount' => 0.1,
            'filled_amount' => 0,
            'status' => 'open',
            'created_at' => now()->subMinutes(30)
        ]);

        // ========== CREATE SOME FILLED ORDERS (History) ==========
        // Past filled order between users
        Order::create([
            'user_id' => $user1->id,
            'symbol' => 'BTC',
            'side' => 'buy',
            'price' => 92000.00,
            'amount' => 0.3,
            'filled_amount' => 0.3,
            'status' => 'filled',
            'created_at' => now()->subDays(2)
        ]);

        Order::create([
            'user_id' => $user2->id,
            'symbol' => 'BTC',
            'side' => 'sell',
            'price' => 92000.00,
            'amount' => 0.3,
            'filled_amount' => 0.3,
            'status' => 'filled',
            'created_at' => now()->subDays(2)
        ]);

        // ========== CREATE CANCELLED ORDERS ==========
        Order::create([
            'user_id' => $user3->id,
            'symbol' => 'ETH',
            'side' => 'buy',
            'price' => 2800.00,
            'amount' => 3.0,
            'filled_amount' => 0,
            'status' => 'cancelled',
            'created_at' => now()->subDays(1)
        ]);

        $this->command->info('========== SEEDING COMPLETE ==========');
        $this->command->info('Created 4 users with realistic trading data:');
        $this->command->info('');
        $this->command->info('USER 1: Alice Trader');
        $this->command->info('Email: alice@example.com | Password: password123');
        $this->command->info('Balance: $150,000 | BTC: 3.5 | ETH: 15.0');
        $this->command->info('Open Orders: BUY 0.5 BTC @ $94,000 | BUY 5.0 ETH @ $2,900');
        $this->command->info('');
        
        $this->command->info('USER 2: Bob Seller');
        $this->command->info('Email: bob@example.com | Password: password123');
        $this->command->info('Balance: $75,000 | BTC: 8.0 (1.2 locked) | ETH: 30.0 (10.0 locked)');
        $this->command->info('Open Orders: SELL 1.2 BTC @ $96,000 | SELL 10.0 ETH @ $3,100');
        $this->command->info('');
        
        $this->command->info('USER 3: Charlie Balanced');
        $this->command->info('Email: charlie@example.com | Password: password123');
        $this->command->info('Balance: $200,000 | BTC: 5.0 (0.8 locked) | ETH: 25.0 (5.0 locked)');
        $this->command->info('Open Orders: BUY 0.8 BTC @ $93,000 | SELL 0.5 BTC @ $97,000');
        $this->command->info('');
        
        $this->command->info('USER 4: Test User (for login)');
        $this->command->info('Email: test@example.com | Password: password');
        $this->command->info('Balance: $100,000 | BTC: 2.0 | ETH: 10.0');
        $this->command->info('Open Orders: BUY 0.1 BTC @ $95,000');
        $this->command->info('');
        $this->command->info('========== ORDER BOOK SUMMARY ==========');
        $this->command->info('BTC BUY Orders (Bids - Highest to Lowest):');
        $this->command->info('  • $94,000 × 0.5 BTC (Alice)');
        $this->command->info('  • $93,000 × 0.8 BTC (Charlie)');
        $this->command->info('  • $95,000 × 0.1 BTC (Test User)');
        $this->command->info('');
        $this->command->info('BTC SELL Orders (Asks - Lowest to Highest):');
        $this->command->info('  • $96,000 × 1.2 BTC (Bob)');
        $this->command->info('  • $97,000 × 0.5 BTC (Charlie)');
        $this->command->info('');
        $this->command->info('ETH BUY Orders (Bids - Highest to Lowest):');
        $this->command->info('  • $2,900 × 5.0 ETH (Alice)');
        $this->command->info('');
        $this->command->info('ETH SELL Orders (Asks - Lowest to Highest):');
        $this->command->info('  • $3,100 × 10.0 ETH (Bob)');
        $this->command->info('');
        $this->command->info('Current Spreads:');
        $this->command->info('  BTC: $96,000 - $94,000 = $2,000 spread');
        $this->command->info('  ETH: $3,100 - $2,900 = $200 spread');
        $this->command->info('');
        $this->command->info('Use test@example.com/password for quick testing!');
    }
}