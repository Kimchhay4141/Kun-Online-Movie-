<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');
            $table->string('plan')->after('user_id');
            $table->decimal('amount', 10, 2)->after('plan');
            $table->string('payment_method')->after('amount');
            $table->string('status')->default('pending')->after('payment_method');
            $table->string('transaction_id')->nullable()->after('status');
            $table->date('subscription_start_date')->nullable()->after('transaction_id');
            $table->date('subscription_end_date')->nullable()->after('subscription_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'plan',
                'amount',
                'payment_method',
                'status',
                'transaction_id',
                'subscription_start_date',
                'subscription_end_date'
            ]);
        });
    }
};
