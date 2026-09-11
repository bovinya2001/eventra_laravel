<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment — Eventra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; } .font-display { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="min-h-screen bg-[#0a0a0f] text-[#f0f0f8]">
    <main class="mx-auto max-w-2xl px-6 py-12">
        <a href="{{ route('my.events') }}" class="text-sm text-slate-400 hover:text-white">← My Events</a>

        <div class="mt-6 rounded-3xl border border-violet-500/20 bg-[#12121a] p-8 shadow-2xl">
            <p class="text-sm font-medium text-violet-400 uppercase tracking-widest">Secure Stripe payment</p>
            <h1 class="font-display mt-2 text-3xl font-black">{{ $registration->event->title }}</h1>
            <dl class="mt-6 grid gap-3 rounded-2xl bg-[#0e0e18] p-5 text-sm border border-white/5">
                <div class="flex justify-between gap-4"><dt class="text-[#8888aa]">Customer</dt><dd class="text-right">{{ auth()->user()->name }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-[#8888aa]">Registration UUID</dt><dd class="font-mono text-xs text-right break-all">{{ $registration->uuid }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-[#8888aa]">Amount</dt><dd class="font-display font-bold text-violet-300">LKR {{ number_format($registration->payment_amount, 2) }}</dd></div>
            </dl>

            @if(session('error'))
                <div class="mt-5 rounded-lg border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300">{{ session('error') }}</div>
            @endif

            <form id="stripe-payment-form" class="mt-6 space-y-5">
                <div>
                    <label for="card-element" class="mb-2 block text-sm text-[#ccccdd]">Card details</label>
                    <div id="card-element" class="rounded-xl bg-white px-4 py-4 ring-1 ring-white/10 focus-within:ring-2 focus-within:ring-violet-500"></div>
                </div>
                <div id="payment-message" class="hidden rounded-lg border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300"></div>
                <p class="text-xs leading-5 text-[#8888aa]">Payment details are collected securely by Stripe. Eventra never receives or stores card numbers, expiry dates, or CVVs.</p>
                <button id="stripe-submit" type="submit" disabled class="w-full rounded-xl bg-violet-600 px-5 py-3 font-semibold hover:bg-violet-500 hover:shadow-[0_0_20px_rgba(124,58,237,.35)] disabled:cursor-not-allowed disabled:opacity-50 transition-all">
                    <span id="button-text">Pay LKR {{ number_format($registration->payment_amount, 2) }}</span>
                    <span id="spinner" class="hidden">Processing…</span>
                </button>
            </form>
        </div>
    </main>
    <script>
        (() => {
            const publishableKey = @json(config('services.stripe.key'));
            const form = document.getElementById('stripe-payment-form');
            const submit = document.getElementById('stripe-submit');
            const message = document.getElementById('payment-message');
            let stripe;
            let elements;

            const showError = (text) => {
                message.textContent = text;
                message.classList.remove('hidden');
            };
            const setLoading = (loading) => {
                submit.disabled = loading;
                document.getElementById('button-text').classList.toggle('hidden', loading);
                document.getElementById('spinner').classList.toggle('hidden', !loading);
            };

            const initialise = async () => {
                if (!publishableKey) return showError('Stripe publishable key is not configured.');
                stripe = Stripe(publishableKey);
                const response = await fetch(@json(route('stripe.intent', $registration)), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: '{}',
                });
                const payload = await response.json();
                if (!response.ok || !payload.client_secret) return showError(payload.message || 'Unable to initialise Stripe payment.');

                window.eventraClientSecret = payload.client_secret;
                elements = stripe.elements();
                window.eventraCard = elements.create('card', {
                    hidePostalCode: true,
                    style: {
                        base: {
                            color: '#0f172a',
                            fontFamily: 'Inter, system-ui, sans-serif',
                            fontSize: '16px',
                            '::placeholder': {color: '#64748b'},
                        },
                        invalid: {color: '#dc2626'},
                    },
                });
                window.eventraCard.mount('#card-element');
                submit.disabled = false;
            };

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                setLoading(true);
                message.classList.add('hidden');
                const result = await stripe.confirmCardPayment(window.eventraClientSecret, {
                    payment_method: {
                        card: window.eventraCard,
                        billing_details: {name: @json(auth()->user()->name), email: @json(auth()->user()->email)},
                    },
                });
                if (result.error) {
                    showError(result.error.message || 'Stripe could not process the payment.');
                } else if (result.paymentIntent?.status === 'succeeded') {
                    const completed = new URL(@json(route('stripe.completed', $registration)));
                    completed.searchParams.set('payment_intent', result.paymentIntent.id);
                    window.location.assign(completed.toString());
                    return;
                }
                setLoading(false);
            });

            initialise().catch(() => showError('Unable to connect to Stripe. Please refresh and try again.'));
        })();
    </script>
</body>
</html>
