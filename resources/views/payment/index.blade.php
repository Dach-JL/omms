<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Choose Payment Method</h1>
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Plan Information (if plan_id provided) -->
            @if(isset($plan))
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <h2 class="text-xl font-semibold text-blue-800">Plan Details</h2>
                    <p class="mt-2 text-gray-700"><strong>Plan:</strong> {{ $plan->name }}</p>
                    <p class="text-gray-700"><strong>Price:</strong> ETB {{ number_format($plan->price, 2) }}</p>
                    <p class="text-gray-700"><strong>Billing:</strong> {{ ucfirst($plan->billing_cycle) }}</p>
                </div>
            @endif

            <!-- Payment Methods -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Telebirr Manual Payment -->
                <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow {{ isset($plan) ? 'border-blue-500 ring-2 ring-blue-200' : '' }}">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Telebirr</h3>
                    </div>
                    
                    <p class="text-gray-600 mb-4">Pay using Telebirr mobile money</p>
                    
                    <ul class="text-sm text-gray-600 mb-6 space-y-2">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Pay with your mobile phone
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Upload receipt for verification
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Admin approval within 24-48 hours
                        </li>
                    </ul>

                    @auth
                        @if(isset($plan))
                            <a href="{{ route('payment.telebirr.form', $plan->id) }}" 
                               class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                                Pay with Telebirr
                            </a>
                        @else
                            <p class="text-sm text-orange-600 mb-3">Select a plan first to proceed with payment</p>
                            <a href="{{ route('organAdmin.plans.upgrade') }}" 
                               class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                                View Available Plans
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" 
                           class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                            Login to Pay
                        </a>
                    @endauth
                </div>

                <!-- Other Payment Methods (Placeholder) -->
                <div class="border rounded-lg p-6 opacity-60">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600">Card Payment</h3>
                    </div>
                    
                    <p class="text-gray-500 mb-4">Credit/Debit Card (Coming Soon)</p>
                    <p class="text-sm text-gray-400 mb-6">This payment method will be available soon.</p>
                    <button disabled class="block w-full bg-gray-300 text-gray-500 text-center font-semibold py-3 px-4 rounded-lg cursor-not-allowed">
                        Coming Soon
                    </button>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">How Telebirr Payment Works</h3>
                <ol class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">1</span>
                        <div>
                            <strong>Select Your Plan:</strong> Choose the subscription plan you want to upgrade to
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">2</span>
                        <div>
                            <strong>Make Payment:</strong> Dial *127# on your phone and pay to the provided Pay Bill number
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">3</span>
                        <div>
                            <strong>Upload Receipt:</strong> Take a screenshot of the payment confirmation and upload it
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">4</span>
                        <div>
                            <strong>Admin Verification:</strong> Our team will verify your payment within 24-48 hours
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">5</span>
                        <div>
                            <strong>Plan Activation:</strong> Once approved, your plan will be upgraded automatically
                        </div>
                    </li>
                </ol>
            </div>

            <!-- Back Link -->
            <div class="mt-6">
                <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Go Back
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
