<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Back Button -->
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('admin.telebirr.verifications') }}"
                    class="text-gray-600 hover:text-gray-800 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Verifications
                </a>
                <h2 class="text-2xl font-bold text-gray-800">Payment Review #{{ $payment->id }}</h2>
            </div>

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Information -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            User Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="font-medium text-gray-900">{{ $payment->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium text-gray-900">{{ $payment->user->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Organization</p>
                                <p class="font-medium text-gray-900">{{ $payment->organ_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Phone Number</p>
                                <p class="font-medium text-gray-900">{{ $payment->payer_phone_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Payment Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Transaction Reference</p>
                                <p class="font-mono font-bold text-blue-600">{{ $payment->telebirr_transaction_ref }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Amount</p>
                                <p class="text-xl font-bold text-green-600">ETB {{ number_format($payment->amount, 2) }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Payment Method</p>
                                <p class="font-medium text-gray-900">Telebirr Manual</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Submitted</p>
                                <p class="font-medium text-gray-900">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Pending Verification
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt Image -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Payment Receipt
                    </h3>

                    @if($payment->receipt_image && Storage::disk('public')->exists($payment->receipt_image))
                    <div class="text-center">
                        <img src="{{ Storage::url($payment->receipt_image) }}"
                            alt="Payment Receipt"
                            class="mx-auto max-w-full rounded-lg shadow-lg border"
                            style="max-height: 600px;">
                        <div class="mt-4">
                            <a href="{{ route('payment.telebirr.download', $payment->id) }}"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Receipt
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12 text-gray-400">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2">Receipt image not found</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Notes -->
            @if($payment->payment_notes)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Payment Notes</h3>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ $payment->payment_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Verification Actions</h3>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Approve Button -->
                        <form action="{{ route('admin.telebirr.approve', $payment->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors inline-flex items-center justify-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Approve Payment
                            </button>
                        </form>

                        <!-- Reject Toggle Button -->
                        <button onclick="toggleRejectForm()"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors inline-flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Reject Payment
                        </button>
                    </div>

                    <!-- Reject Form (Hidden by default) -->
                    <div id="reject-form" class="hidden mt-6">
                        <form action="{{ route('admin.telebirr.reject', $payment->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Rejection Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea id="rejection_reason"
                                    name="rejection_reason"
                                    rows="4"
                                    required
                                    placeholder="Please provide a detailed reason for rejecting this payment..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('rejection_reason') border-red-500 @enderror">{{ old('rejection_reason') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Minimum 10 characters. This will be shown to the user.</p>
                                @error('rejection_reason')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex gap-3">
                                <button type="submit"
                                    class="flex-1 bg-red-700 hover:bg-red-800 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                    Confirm Rejection
                                </button>
                                <button type="button"
                                    onclick="toggleRejectForm()"
                                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Text -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-semibold text-blue-800">Verification Guidelines</h4>
                        <ul class="mt-2 text-sm text-blue-700 space-y-1">
                            <li>• Verify the transaction reference matches Telebirr records</li>
                            <li>• Check that the receipt is clear and legible</li>
                            <li>• Confirm the amount matches the plan price</li>
                            <li>• Ensure the phone number matches the user's record</li>
                            <li>• Approve only if all details are correct and verified</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleRejectForm() {
            const form = document.getElementById('reject-form');
            form.classList.toggle('hidden');
        }
    </script>
</x-app-layout>