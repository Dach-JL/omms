<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use App\Models\Otp;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(): void
    {
       
        Event::listen(Login::class, function ($event) {
            
    
            $otpCode = (string)rand(100000, 999999);

          
            try {
                Otp::create([
                    'email'      => $event->user->email,
                    'otp_code'   => $otpCode,
                    'expires_at' => Carbon::now()->addMinutes(15),
                    'is_used'    => false,
                ]);

              
                session(['otp_email' => $event->user->email]);

                Log::info("OTP si guul leh ayaa loogu abuuray: " . $event->user->email);
            } catch (\Exception $e) {
                Log::error("OTP error: " . $e->getMessage());
            }
        });
    }
}