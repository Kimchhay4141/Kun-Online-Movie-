<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users to associate payments with
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        $plans = ['basic', 'standard', 'premium'];
        $paymentMethods = ['credit_card', 'paypal', 'stripe'];
        $statuses = ['completed', 'pending', 'failed', 'refunded'];

        // Create sample payments for each user
        foreach ($users as $user) {
            // Create 1-3 payments per user
            $numPayments = rand(1, 3);
            
            for ($i = 0; $i < $numPayments; $i++) {
                $plan = $plans[array_rand($plans)];
                $amount = match($plan) {
                    'basic' => 9.99,
                    'standard' => 14.99,
                    'premium' => 19.99,
                };
                
                $status = $statuses[array_rand($statuses)];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                
                // Create a random date within the last 30 days
                $createdAt = Carbon::now()->subDays(rand(0, 30));
                $startDate = $createdAt->copy();
                $endDate = $startDate->copy()->addMonth();

                Payment::create([
                    'user_id' => $user->id,
                    'plan' => $plan,
                    'amount' => $amount,
                    'payment_method' => $paymentMethod,
                    'status' => $status,
                    'transaction_id' => 'TXN_' . strtoupper(uniqid()),
                    'subscription_start_date' => $startDate,
                    'subscription_end_date' => $endDate,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        $this->command->info('Sample payments created successfully.');
    }
}
