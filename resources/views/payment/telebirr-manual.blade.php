<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <h2 class="text-2xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Telebirr Payment
                    </h2>
                    <p class="mt-2 text-blue-100">Complete your payment using Telebirr mobile money</p>
                </div>
            </div>

            <!-- Plan Summary -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Plan Summary</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Plan Name</p>
                            <p class="text-lg font-bold text-blue-700">{{ $plan->name }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="text-lg font-bold text-green-700">ETB {{ number_format($plan->price, 2) }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Billing Cycle</p>
                            <p class="text-lg font-bold text-purple-700">{{ ucfirst($plan->billing_cycle) }}</p>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Type</p>
                            <p class="text-lg font-bold text-orange-700">{{ ucfirst($plan->type) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Instructions</h3>
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Important:</strong> Follow these steps carefully to complete your payment
                                </p>
                            </div>
                        </div>
                    </div>

                    <ol class="space-y-3">
                        @foreach($instructions as $index => $step)
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-gray-700 mt-1">{{ $step }}</span>
                            </li>
                        @endforeach
                    </ol>

                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Payment Details:</p>
                        <ul class="space-y-1 text-sm text-gray-600">
                            <li><strong>Pay Bill Number:</strong> <span class="text-blue-600 font-bold">{{ $paybill }}</span></li>
                            <li><strong>Account Name:</strong> <span class="text-blue-600 font-bold">{{ $accountName }}</span></li>
                            <li><strong>Amount to Pay:</strong> <span class="text-green-600 font-bold">ETB {{ number_format($plan->price, 2) }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload Payment Receipt</h3>
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('payment.telebirr.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Your Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="phone_number" 
                                   name="phone_number" 
                                   value="{{ old('phone_number') }}"
                                   placeholder="912345678"
                                   pattern="[0-9]{9}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone_number') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Enter the phone number you used for Telebirr payment (9 digits)</p>
                            @error('phone_number')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Transaction Reference -->
                        <div class="mb-4">
                            <label for="transaction_ref" class="block text-sm font-medium text-gray-700 mb-2">
                                Transaction Reference <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="transaction_ref" 
                                   name="transaction_ref" 
                                   value="{{ old('transaction_ref') }}"
                                   placeholder="e.g., TRX123456789"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('transaction_ref') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Copy the transaction reference from your Telebirr SMS confirmation</p>
                            @error('transaction_ref')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Receipt Upload -->
                        <div class="mb-4">
                            <label for="receipt_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Receipt Screenshot <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="receipt_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                            <span>Upload a file</span>
                                            <input id="receipt_image" name="receipt_image" type="file" accept="image/*" required class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                </div>
                            </div>
                            @error('receipt_image')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Notes -->
                        <div class="mb-6">
                            <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Notes (Optional)
                            </label>
                            <textarea id="payment_notes" 
                                      name="payment_notes" 
                                      rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_notes') border-red-500 @enderror">{{ old('payment_notes') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Any additional information about your payment</p>
                            @error('payment_notes')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between">
                            <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-800 inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Submit Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-semibold text-blue-800">Need Help?</h4>
                        <p class="mt-1 text-sm text-blue-700">
                            If you're having trouble completing your payment, please contact support at 
                            <a href="mailto:support@example.com" class="underline hover:text-blue-900">support@example.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
