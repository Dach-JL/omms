<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TelebirrVerificationController extends Controller
{
    /**
     * Display all pending Telebirr payments for verification
     */
    public function index()
    {
        $payments = Payment::where('verification_status', 'pending')
            ->orderBy('verification_requested_at', 'desc')
            ->paginate(20);

        return view('admin.telebirr-verifications', compact('payments'));
    }

    /**
     * View single payment details for verification
     */
    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.telebirr-verify-detail', compact('payment'));
    }

    /**
     * Approve payment and activate user's plan
     */
    public function approve(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        DB::beginTransaction();

        try {
            // Update payment status
            $payment->update([
                'verification_status' => 'approved',
                'status' => 'success',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            // Activate user's plan
            $user = $payment->user;
            $plan = $payment->plan;

            // Calculate expiry based on billing cycle
            if ($plan->billing_cycle === 'monthly') {
                $expiry = now()->addMonth();
            } elseif ($plan->billing_cycle === 'yearly') {
                $expiry = now()->addYear();
            } else {
                $expiry = null; // Lifetime plan
            }

            $user->update([
                'plan_id' => $plan->id,
                'plan_expiry' => $expiry,
            ]);

            DB::commit();

            // Log the approval
            \Log::info('Telebirr payment approved', [
                'payment_id' => $payment->id,
                'user_id' => $user->id,
                'amount' => $payment->amount,
                'admin_id' => Auth::id(),
            ]);

            // Optional: Send approval notification email here
            // Mail::to($user->email)->send(new PaymentApproved($payment));

            return redirect()
                ->route('admin.telebirr.verifications')
                ->with('success', 'Payment approved successfully! User plan activated.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Telebirr payment approval failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'admin_id' => Auth::id(),
            ]);

            return back()
                ->withErrors(['error' => 'Failed to approve payment: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject payment with reason
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection',
            'rejection_reason.min' => 'Rejection reason must be at least 10 characters',
            'rejection_reason.max' => 'Rejection reason must not exceed 500 characters',
        ]);

        $payment = Payment::findOrFail($id);

        $payment->update([
            'verification_status' => 'rejected',
            'status' => 'failed',
            'rejection_reason' => $validated['rejection_reason'],
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Log the rejection
        \Log::warning('Telebirr payment rejected', [
            'payment_id' => $payment->id,
            'user_id' => $payment->user_id,
            'reason' => $validated['rejection_reason'],
            'admin_id' => Auth::id(),
        ]);

        // Optional: Send rejection notification email with reason
        // Mail::to($payment->user->email)->send(new PaymentRejected($payment));

        return redirect()
            ->route('admin.telebirr.verifications')
            ->with('message', 'Payment rejected. User has been notified.');
    }

    /**
     * Show all verified/approved payments (history)
     */
    public function history()
    {
        $payments = Payment::where('verification_status', 'approved')
            ->orderBy('verified_at', 'desc')
            ->paginate(50);

        return view('admin.telebirr-history', compact('payments'));
    }

    /**
     * Show rejected payments
     */
    public function rejected()
    {
        $payments = Payment::where('verification_status', 'rejected')
            ->orderBy('verified_at', 'desc')
            ->paginate(50);

        return view('admin.telebirr-rejected', compact('payments'));
    }

    /**
     * Search payments by transaction reference
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');

        $payments = Payment::where('telebirr_transaction_ref', 'LIKE', "%{$search}%")
            ->orWhere('payer_phone_number', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.telebirr-verifications', compact('payments'))
            ->with('search', $search);
    }
}
