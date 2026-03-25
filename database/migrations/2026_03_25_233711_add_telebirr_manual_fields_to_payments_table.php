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
            // Add fields for manual Telebirr payment tracking
            $table->string('receipt_image')->nullable()->after('payment_method');
            $table->string('telebirr_transaction_ref')->nullable()->after('receipt_image');
            $table->string('payer_phone_number', 20)->nullable()->after('telebirr_transaction_ref');
            $table->text('payment_notes')->nullable()->after('payer_phone_number');
            $table->timestamp('verification_requested_at')->nullable()->after('payment_notes');
            $table->timestamp('verified_at')->nullable()->after('verification_requested_at');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->onDelete('set null');
            
            // Status tracking for verification workflow
            $table->enum('verification_status', [
                'pending',      // Awaiting admin review
                'approved',     // Admin approved
                'rejected',     // Admin rejected (invalid receipt)
            ])->default('pending')->after('status');
            
            $table->text('rejection_reason')->nullable()->after('verification_status');
            
            // Add index for faster lookups
            $table->index('telebirr_transaction_ref');
            $table->index('verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['telebirr_transaction_ref']);
            $table->dropIndex(['verification_status']);
            
            // Drop foreign key
            $table->dropForeign(['verified_by']);
            
            // Then drop columns
            $table->dropColumn([
                'receipt_image',
                'telebirr_transaction_ref',
                'payer_phone_number',
                'payment_notes',
                'verification_requested_at',
                'verified_at',
                'verified_by',
                'verification_status',
                'rejection_reason',
            ]);
        });
    }
};
