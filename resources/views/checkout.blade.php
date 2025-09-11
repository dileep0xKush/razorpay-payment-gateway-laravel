@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold mb-6">Checkout</h2>

    <div class="bg-white p-6 rounded shadow max-w-md mx-auto">
        <h3 class="text-xl font-semibold mb-2">{{ $product->name }}</h3>
        <p class="mb-4">{{ $product->description }}</p>
        <p class="text-green-700 text-lg font-bold mb-6">₹{{ number_format($product->price, 2) }}</p>

        <button id="rzp-button"
            class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">
            Proceed to Pay
        </button>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const options = {
            key: "{{ $razorpayKey }}", // From env
            amount: {{ intval($product->price * 100) }},
            currency: "INR",
            name: "{{ $product->name }}",
            description: "{{ $product->description }}",
            handler: function(response) {
                fetch("{{ route('payment.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            payment_id: response.razorpay_payment_id,
                            order_id: response.razorpay_order_id,
                            signature: response.razorpay_signature,
                            product_id: {{ $product->id }},
                            amount: {{ intval($product->price * 100) }},
                            currency: "INR"
                        })
                    })
                    .then(res => res.json())
                    // .then(data => {
                    //     alert(data.message);
                    //     window.location.href = "/"; // Redirect after success
                    // })
                    .catch(err => {
                        console.error(err);
                        alert("Payment succeeded, but saving failed.");
                    });
            },
            theme: {
                color: "#3399cc"
            }
        };

        const rzp = new Razorpay(options);

        document.getElementById("rzp-button").onclick = function(e) {
            rzp.open();
            e.preventDefault();
        };
    </script>
@endsection
