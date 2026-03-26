

<x-app-layout>
    <div class="max-w-6xl px-6 py-12 mx-auto">
        <h2 class="mb-8 text-3xl font-bold text-center">Upgrade Your Plan</h2>

        {{-- Payment Methods Info --}}
        <div class="max-w-3xl mx-auto mb-8 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-blue-900">Payment Methods Available</h3>
                    <p class="text-sm text-blue-700 mt-1">
                        We accept <strong>Telebirr Manual Payment</strong> for plan upgrades. After selecting your plan, you'll be guided through the payment process.
                    </p>
                </div>
            </div>
        </div>

        {{-- Toggle Personal / Business (optional) --}}
        <div class="flex justify-center mb-10">
            <div class="flex p-1 space-x-2 bg-gray-200 rounded-full">
                <button type="button" class="px-4 py-1 text-sm font-medium bg-white rounded-full shadow">
                    Personal
                </button>

            </div>
        </div>

        {{-- Plans Grid --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @foreach($plans as $plan)
                <div class="flex flex-col p-6 bg-white border border-gray-200 shadow-lg rounded-2xl">

                    {{-- Header --}}
                    <h3 class="mb-2 text-xl font-semibold">{{ $plan->name }}</h3>
                    <p class="mb-4 text-gray-500">
                        {{ $plan->billing_cycle === 'free' ? 'Free forever' : ucfirst($plan->billing_cycle) }} plan
                    </p>

                    {{-- Price --}}
                    <div class="mb-6 text-4xl font-bold">
                        ${{ $plan->price }}
                        <span class="text-base font-medium text-gray-500">/month</span>
                    </div>

                    {{-- Features --}}
                    <ul class="flex-grow space-y-2 text-gray-600">
                        @if($plan->type === 'organAdmin')
                            <li>👥 Up to {{ $plan->max_members ?? 'Unlimited' }} members</li>
                        @endif
                        @if($plan->duration_days)
                            <li>📅 Duration: {{ $plan->duration_days }} days</li>
                        @endif
                        <li>⚡ Priority support</li>
                        <li>🔒 Secure system access</li>
                        @if($plan->price > 0)
                            <li class="flex items-center text-green-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Telebirr payment accepted
                            </li>
                        @endif
                    </ul>

                    {{-- Action --}}
                    <form action="{{ route('organAdmin.plans.upgrade') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        
                        @if($user->plan_id == $plan->id)
                            <button
                                type="submit"
                                disabled
                                class="w-full py-3 rounded-full font-semibold bg-gray-300 text-gray-600 cursor-not-allowed">
                                Your Current Plan
                            </button>
                        @elseif($plan->price == 0)
                            <button
                                type="submit"
                                class="w-full py-3 rounded-full font-semibold bg-green-600 text-white hover:bg-green-700 transition">
                                Get {{ $plan->name }} (Free)
                            </button>
                        @else
                            <div class="space-y-3">
                                <button
                                    type="submit"
                                    class="w-full py-3 rounded-full font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Pay with Telebirr
                                </button>
                                <p class="text-xs text-center text-gray-500">
                                    You'll be redirected to complete payment via Telebirr
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

