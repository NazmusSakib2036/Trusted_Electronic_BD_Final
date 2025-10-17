@extends('frontend.layouts.app')

@section('title', 'About Us - Trusted Electronics BD')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    
    <div class="bg-pink-50/70 p-6 md:p-10 rounded-xl shadow-lg border border-pink-100">
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
            
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/products/famous-electronics-logo.png') }}" 
                     alt="Famous Electronics Logo"
                     class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-white shadow-md"
                     style="width: 150px; height: 150px;">
            </div>

            <div class="text-gray-800 text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-bold mb-4">TRUSTED ELECTRONIC BD</h1>
                <p class="text-base md:text-lg mb-2">
                    <span class="font-semibold">Address :</span> Bahaddarhat Pukurpar, Chattogram
                </p>
                <p class="text-base md:text-lg mb-2">
                    <span class="font-semibold">Phone :</span> <a href="tel:++8801888058362" class="text-blue-600 hover:text-blue-800 transition duration-150">+8801888058362</a>
                </p>
                <p class="text-base md:text-lg">
                    <span class="font-semibold">Email :</span> <a href="mailto:trustedelectronicbd@gmail.com" class="text-blue-600 hover:text-blue-800 transition duration-150">trustedelectronicbd@gmail.com</a>
                </p>
            </div>
        </div>
    </div>
    
    <div class="mt-8 text-center">
        <p class="text-xl md:text-2xl font-semibold text-red-600">
            ইলেকট্রনিক্স পণ্যের নির্ভরযোগ্য অনলাইন প্ল্যাটফর্ম।
        </p>
    </div>

</div>
@endsection