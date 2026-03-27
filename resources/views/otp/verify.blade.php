<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMMS - Security Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body class="bg-[#f0fdf4] flex flex-col items-center justify-center min-h-screen p-4">

    <div class="absolute top-8 left-8">
        <h1 class="text-[#059669] text-2xl font-bold tracking-tight">OMMS</h1>
    </div>

    <div class="bg-white p-12 rounded-[2rem] shadow-2xl shadow-green-100 max-w-lg w-full text-center border border-gray-50 relative overflow-hidden">
        
        <div class="bg-[#ecfdf5] w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-8">
            <svg class="w-8 h-8 text-[#10b981]" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
        </div>

        <h2 class="text-[28px] font-bold text-gray-900 mb-3 tracking-tight">Security Verification</h2>
        <p class="text-gray-500 mb-10 text-[16px] leading-relaxed px-4">
            Enter the 6-digit code sent to your device to access the Super Admin Dashboard.
        </p>

        @if(session('error'))
            <div class="mb-6 p-4 text-sm text-red-700 bg-red-50 rounded-xl border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('otp.check') }}" method="POST" id="otp-form">
            @csrf
            <div class="flex justify-center gap-3 mb-10">
                @for($i = 0; $i < 6; $i++)
                    <input type="text" 
                           name="otp[]" 
                           maxlength="1" 
                           pattern="\d*" 
                           inputmode="numeric"
                           class="otp-input w-14 h-16 text-center text-2xl font-bold bg-[#f0fdf4] text-gray-800 border-none rounded-2xl focus:ring-4 focus:ring-green-200 transition-all outline-none" 
                           placeholder="·" 
                           required>
                @endfor
            </div>

            <button type="submit" class="w-full bg-[#10b981] hover:bg-[#059669] text-white font-semibold py-5 rounded-2xl transition-all mb-6 shadow-xl shadow-green-200 text-lg">
                Verify Account
            </button>
        </form>

        <p class="text-[15px] text-gray-600 mb-6">
            Didn't receive the code? <a href="#" class="text-[#10b981] font-bold hover:underline ml-1">Resend Code</a>
        </p>

        <div class="inline-flex items-center gap-2 bg-[#f1f3f5] px-4 py-2 rounded-xl text-[12px] text-gray-500 font-medium">
            <svg class="w-3 h-3 text-[#10b981]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2V7a5 5 0 00-5-5zM7 7a3 3 0 016 0v2H7V7z"></path></svg>
            End-to-end encrypted session
        </div>
    </div>

    <div class="mt-12 text-[11px] text-gray-400 uppercase tracking-[0.2em] font-bold">
        Protected by OMMS Advanced Guard System
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.inputType === "deleteContentBackward") return;
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
            input.addEventListener('keypress', (e) => {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>