<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Success Icon -->
                    <div class="text-center mb-6">
                        <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Payment Submitted Successfully!</h2>
                        <p class="mt-2 text-gray-600">Your payment is being reviewed</p>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment ID:</span>
                                <span class="font-semibold text-gray-800">#{{ $payment->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction Reference:</span>
                                <span class="font-semibold text-blue-600">{{ $payment->telebirr_transaction_ref }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-bold text-green-600">ETB {{ number_format($payment->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-semibold text-gray-800">Telebirr Manual</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Pending Verification
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Submitted:</span>
                                <span class="font-semibold text-gray-800">{{ $payment->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-blue-800">What Happens Next?</h4>
                                <ul class="mt-2 text-sm text-blue-700 space-y-1">
                                    <li>• Our admin team will review your payment within <strong>24-48 hours</strong></li>
                                    <li>• Once approved, your plan will be activated automatically</li>
                                    <li>• You'll receive an email confirmation when your payment is approved</li>
                                    <li>• You can track your payment status in your dashboard</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Important Note -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-yellow-800">Important</h4>
                                <p class="mt-1 text-sm text-yellow-700">
                                    Please keep your transaction reference number (<strong>{{ $payment->telebirr_transaction_ref }}</strong>) safe until your payment is approved. You may need it for support inquiries.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('payment.telebirr.history') }}" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-3 px-4 rounded-lg transition-colors inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View My Payments
                        </a>
                        <a href="{{ url('/home') }}" 
                           class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center font-semibold py-3 px-4 rounded-lg transition-colors inline-flex items-center justify-center">
                            Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Have questions or need help? 
                    <a href="mailto:support@example.com" class="text-blue-600 hover:underline">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
