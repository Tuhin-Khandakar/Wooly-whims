@extends('layouts.app')

@section('title', 'Shipping Policies | Wooly Whims')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <h1 class="font-serif text-5xl font-black text-gray-900 mb-12 italic border-b border-gray-100 pb-8">Shipping <span class="text-brand-sage">Policies</span></h1>
    
    <div class="prose prose-lg prose-brand-sage italic text-gray-600 space-y-12">
        <section>
            <h2 class="text-2xl font-serif font-bold text-gray-800 mb-6">Crafting & Handling</h2>
            <p>Every piece at Wooly Whims is handcrafted with artistic precision. Because of this personalized touch, please allow **10-15 business days** for your order to be prepared and dispatched.</p>
        </section>

        <section>
            <h2 class="text-2xl font-serif font-bold text-gray-800 mb-6">Delivery Areas & Charges</h2>
            <ul class="list-disc pl-6 space-y-4">
                <li><strong>Inside Dhaka:</strong> Delivery within 2-3 business days after dispatch. Charge: Tk 80.</li>
                <li><strong>Outside Dhaka:</strong> Delivery within 3-5 business days after dispatch. Charge: Tk 120.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-serif font-bold text-gray-800 mb-6">Order Verification</h2>
            <p>To provide a human-centric service, we verify every order via Instagram or Facebook messaging. Please ensure your provided handle is correct and your messages are open.</p>
        </section>

        <div class="bg-brand-sage/5 p-8 rounded-[40px] border border-brand-sage/10 mt-20">
            <p class="font-bold text-brand-sage-dark italic">Need more details? Message us on Instagram for a personal response.</p>
        </div>
    </div>
</div>
@endsection
