<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the OTPs table.
     * This table stores temporary verification codes for security.
     */
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();         // Link the code to a user email
            $table->string('otp_code');               // The actual 6-digit verification code
            $table->timestamp('expires_at');          // Security: Code expiration timestamp
            $table->boolean('is_used')->default(false); // Status to prevent code reuse
            $table->timestamps();                     // Record creation and update times
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};