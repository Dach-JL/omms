<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp; 
use Carbon\Carbon;

class OtpController extends Controller
{
    /**
     * Show the OTP verification page.
     */
    public function show()
    {
        return view('otp.verify');
    }

    /**
     * Verify the 6-digit code against the database.
     */
    public function verify(Request $request)
    {
        // Combine the array of 6 digits into a single string
        $enteredCode = implode('', $request->otp);
        
        // Find the latest valid OTP that hasn't expired and hasn't been used
        $otpRecord = Otp::where('otp_code', $enteredCode)
                        ->where('is_used', false)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if ($otpRecord) {
           
            $otpRecord->update(['is_used' => true]); 
            
            return redirect()->route('dashboard')->with('success', 'Access Granted!');
        }

        // Failure: Code is wrong or expired
        return back()->with('error', 'Invalid or expired code. Please try again.');
    }
}