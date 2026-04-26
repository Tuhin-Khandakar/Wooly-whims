@extends('layouts.app')

@section('title', 'Privacy Policy | Wooly Whims')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <h1 class="font-serif text-5xl font-black text-gray-900 mb-12 italic border-b border-gray-100 pb-8">Privacy <span class="text-brand-sage">Policy</span></h1>
    
    <div class="prose prose-lg prose-brand-sage italic text-gray-600 space-y-12">
        <section>
            <h2 class="text-2xl font-serif font-bold text-gray-800 mb-6">Information Collection</h2>
            <p>At Wooly Whims, we value your privacy as much as we value our craft. We collect only the essential information needed to fulfill your order: Name, Phone Number, Delivery Address, and Social Media handles.</p>
        </section>

        <section>
            <h2 class="text-2xl font-serif font-bold text-gray-800 mb-6">How We Use Your Data</h2>
            <p>Your details are strictly used for order processing and verification. We will never share your information with third-party advertisers. Your social handle is used only by our store owner to confirm your order details via DM.</p>
        </section>

        <section>
            <h2 class="text-2xl font-serif font-bold text-gray-800 mb-6">Security</h2>
            <p>We implement secure practices to protect your data. All order records are managed within our private administrative portal, accessible only to the business owners.</p>
        </section>

        <div class="bg-gray-50 p-8 rounded-[40px] border border-gray-100 mt-20">
            <p class="font-bold text-gray-800 italic">By using our atelier, you agree to the collection and use of information in accordance with this policy.</p>
        </div>
    </div>
</div>
@endsection
