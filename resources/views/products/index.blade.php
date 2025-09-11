@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-8 text-center">Our Products</h1>

    @if ($products->isEmpty())
        <p class="text-center text-gray-600">No products available at the moment.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach ($products as $product)
                <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-semibold mb-2 text-gray-800">{{ $product->name }}</h2>
                        <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>
                    </div>
                    <div class="mt-auto">
                        <p class="text-green-700 text-lg font-bold mb-4">₹{{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('checkout', $product->id) }}"
                            class="block w-full bg-green-600 hover:bg-green-700 text-white text-center font-semibold py-2 rounded transition duration-300">
                            Buy Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
