<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TelebirrManualController extends Controller
{
    /**
     * Display payment instructions form for Telebirr manual payment
     */
    public function showPaymentForm($plan_id)
    {
        $plan = Plan::findOrFail($plan_id);
        $paybill = config('telebirr_manual.pay_bill_number');
        $accountName = config('telebirr_manual.account_name');
        $instructions = config('telebirr_manual.instructions');

        return view('payment.telebirr-manual', compact(
            'plan',
            'paybill',
            'accountName',
            'instructions'
        ));
    }

    /**
     * Process payment submission with receipt upload
     */
    public function submitPayment(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'phone_number' => 'required|string|max:20',
            'transaction_ref' => 'required|string|max:50',
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_notes' => 'nullable|string|max:500',
        ], [
            'phone_number.required' => 'Please enter your phone number',
            'transaction_ref.required' => 'Please enter the transaction reference from Telebirr',
            'receipt_image.required' => 'Please upload a screenshot/photo of your payment receipt',
            'receipt_image.image' => 'The receipt must be an image (JPG, PNG)',
            'receipt_image.max' => 'Receipt image size must not exceed 2MB',
        ]);

        try {
            $user = Auth::user();
            $plan = Plan::findOrFail($request->plan_id);

            // Handle receipt upload
            $receiptPath = $request->file('receipt_image')->store(
                config('telebirr_manual.upload_path'),
                config('telebirr_manual.storage_disk')
            );

            DB::beginTransaction();

            // Create payment record in pending state
            $payment = Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'name' => $user->name,
                'organ_name' => $user->organization_name ?? null,
                'amount' => $plan->price,
                'billing' => $plan->billing_cycle,
                'payment_method' => 'telebirr_manual',
                'status' => 'pending_verification',
                'receipt_image' => $receiptPath,
                'telebirr_transaction_ref' => $validated['transaction_ref'],
                'payer_phone_number' => $validated['phone_number'],
                'payment_notes' => $validated['payment_notes'] ?? null,
                'verification_status' => 'pending',
                'verification_requested_at' => now(),
            ]);

            DB::commit();

            // Log the payment attempt
            \Log::info('Telebirr payment submitted', [
                'payment_id' => $payment->id,
                'user_id' => $user->id,
                'amount' => $plan->price,
                'transaction_ref' => $validated['transaction_ref'],
            ]);

            return redirect()
                ->route('payment.telebirr.success')
                ->with('payment_id', $payment->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Telebirr payment submission failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'plan_id' => $request->plan_id,
            ]);

            return back()
                ->withErrors(['error' => 'Payment submission failed. Please try again or contact support.'])
                ->withInput();
        }
    }

    /**
     * Show success page after payment submission
     */
    public function showSuccess()
    {
        $paymentId = session('payment_id');
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return redirect()->route('home')
                ->with('error', 'No payment found. Please submit your payment again.');
        }

        return view('payment.telebirr-success', compact('payment'));
    }

    /**
     * User dashboard - show their Telebirr payment history
     */
    public function userPayments()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->where('payment_method', 'telebirr_manual')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.telebirr-history', compact('payments'));
    }

    /**
     * Download receipt image (for admin use)
     */
    public function downloadReceipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        // Check authorization (only admin or owner can download)
        if (!Auth::user()->role === 'SuperAdmin' && $payment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!Storage::disk(config('telebirr_manual.storage_disk'))->exists($payment->receipt_image)) {
            abort(404, 'Receipt file not found');
        }

        return Storage::disk(config('telebirr_manual.storage_disk'))
            ->download($payment->receipt_image, 'receipt_' . $payment->telebirr_transaction_ref . '.' . pathinfo($payment->receipt_image, PATHINFO_EXTENSION));
    }
}
