@extends('layouts.app')

@section('title', 'Processing Payment | JACARIO')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-2xl border border-zinc-200 shadow-2xl p-8 text-center space-y-6">
        
        <!-- Header / Logo -->
        <div class="space-y-2">
            <span class="text-2xl font-serif-luxury font-bold tracking-[0.2em] text-[#0B0D10]">JACARIO</span>
            <p class="text-xs text-zinc-500 uppercase tracking-widest">Encrypted Payment Gateway</p>
        </div>

        <div class="p-6 bg-zinc-50 rounded-xl border border-zinc-200 text-left space-y-3">
            <div class="flex justify-between text-xs text-zinc-600">
                <span>Order Reference:</span>
                <span class="font-bold text-zinc-900 font-mono">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between text-xs text-zinc-600">
                <span>Customer:</span>
                <span class="font-semibold text-zinc-900">{{ $order->customer_name }}</span>
            </div>
            <div class="border-t border-zinc-200 pt-2 flex justify-between text-sm font-bold text-zinc-950">
                <span>Amount Payable:</span>
                <span>₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Verification Form Post Target -->
        <form id="razorpayForm" method="POST" action="{{ route('checkout.razorpay.verify') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="{{ $razorpayOrder['id'] }}">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">

            @if($razorpayOrder['is_mock'] ?? false)
                <!-- Simulation mode UI for local test without active live API credentials -->
                <div class="space-y-4">
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 text-left">
                        <p class="font-bold mb-0.5">Test Gateway Simulation Mode</p>
                        <p>No active Razorpay live credentials in environment. Click below to verify payment simulation.</p>
                    </div>

                    <button type="button" 
                            onclick="simulatePaymentSuccess()" 
                            class="w-full py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-colors shadow-lg">
                        Simulate Payment Success (₹{{ number_format($order->total_amount, 2) }})
                    </button>
                </div>
            @else
                <button type="button" 
                        id="payButton" 
                        class="w-full py-3.5 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-wider rounded-xl transition-colors shadow-lg">
                    Open Razorpay Gateway
                </button>
            @endif
        </form>

        <p class="text-[11px] text-zinc-400">
            Guaranteed 256-bit SSL encrypted transaction via Razorpay
        </p>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function simulatePaymentSuccess() {
        document.getElementById('razorpay_payment_id').value = 'pay_sim_' + Math.random().toString(36).substring(2);
        document.getElementById('razorpay_signature').value = 'sig_sim_' + Math.random().toString(36).substring(2);
        document.getElementById('razorpayForm').submit();
    }

    @if(!($razorpayOrder['is_mock'] ?? false))
        const options = {
            key: "{{ $razorpayKey }}",
            amount: "{{ $razorpayOrder['amount'] }}",
            currency: "INR",
            name: "JACARIO",
            description: "Order {{ $order->order_number }}",
            image: "{{ asset('images/polos/black-polo.svg') }}",
            order_id: "{{ $razorpayOrder['id'] }}",
            handler: function (response) {
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('razorpayForm').submit();
            },
            prefill: {
                name: "{{ $order->customer_name }}",
                email: "{{ $order->customer_email }}",
                contact: "{{ $order->customer_phone }}"
            },
            theme: {
                color: "#0B0D10"
            }
        };

        const rzp = new Razorpay(options);

        rzp.on('payment.failed', function (response) {
            window.location.href = "{{ route('checkout.failed') }}?order={{ $order->order_number }}&reason=" + encodeURIComponent(response.error.description);
        });

        document.getElementById('payButton').onclick = function(e) {
            rzp.open();
            e.preventDefault();
        };

        // Auto open on page load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { rzp.open(); }, 500);
        });
    @endif
</script>
@endpush
