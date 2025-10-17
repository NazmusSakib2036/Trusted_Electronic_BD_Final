@extends('admin.layouts.app')

@section('title', 'Compose SMS')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Compose SMS</h1>
        <a href="{{ route('admin.sms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to SMS Logs
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Send New SMS</h2>
            </div>

            <form method="POST" action="{{ route('admin.sms.send') }}" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Phone Numbers -->
                    <div>
                        <label for="phone_numbers" class="block text-sm font-medium text-gray-700">
                            Phone Numbers *
                        </label>
                        <textarea name="phone_numbers" id="phone_numbers" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('phone_numbers') border-red-500 @enderror"
                                  placeholder="Enter phone numbers separated by commas or new lines&#10;Example:&#10;01712345678&#10;01812345679&#10;8801912345680">{{ old('phone_numbers') }}</textarea>
                        @error('phone_numbers')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Enter phone numbers separated by commas or new lines. Bangladesh numbers will be automatically formatted.
                        </p>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">
                            Message *
                        </label>
                        <textarea name="message" id="message" rows="5" required maxlength="160"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('message') border-red-500 @enderror"
                                  placeholder="Type your SMS message here...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-1 flex justify-between text-sm text-gray-500">
                            <span>Maximum 160 characters for standard SMS</span>
                            <span id="charCount">0/160</span>
                        </div>
                    </div>

                    <!-- Quick Templates -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quick Templates</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <button type="button" onclick="useTemplate('order_confirmed')" 
                                    class="text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-md border">
                                <div class="font-medium text-sm">Order Confirmed</div>
                                <div class="text-xs text-gray-500">Great news! Your order has been confirmed...</div>
                            </button>
                            <button type="button" onclick="useTemplate('order_shipped')" 
                                    class="text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-md border">
                                <div class="font-medium text-sm">Order Shipped</div>
                                <div class="text-xs text-gray-500">Your order has been shipped...</div>
                            </button>
                            <button type="button" onclick="useTemplate('order_delivered')" 
                                    class="text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-md border">
                                <div class="font-medium text-sm">Order Delivered</div>
                                <div class="text-xs text-gray-500">Order delivered successfully...</div>
                            </button>
                            <button type="button" onclick="useTemplate('promotion')" 
                                    class="text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-md border">
                                <div class="font-medium text-sm">Promotion</div>
                                <div class="text-xs text-gray-500">Special offer just for you...</div>
                            </button>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div id="previewSection" class="bg-gray-50 p-4 rounded-md border hidden">
                        <h4 class="font-medium text-gray-900 mb-2">Preview</h4>
                        <div class="bg-white p-3 rounded border">
                            <div class="text-sm text-gray-600">SMS Preview:</div>
                            <div id="messagePreview" class="mt-1 text-gray-900"></div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            <span id="recipientCount">0</span> recipient(s) will receive this message
                        </div>
                    </div>

                    <!-- Send Button -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="previewMessage()" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Preview
                        </button>
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Send SMS
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Character count
document.getElementById('message').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('charCount').textContent = count + '/160';
    
    if (count > 160) {
        this.classList.add('border-yellow-500');
        document.getElementById('charCount').classList.add('text-yellow-600');
    } else {
        this.classList.remove('border-yellow-500');
        document.getElementById('charCount').classList.remove('text-yellow-600');
    }
});

// Templates
const templates = {
    order_confirmed: "Great news! Your order has been confirmed. We're preparing your items. Thanks for choosing TrustedElectronics!",
    order_shipped: "Your order has been shipped! Track your delivery for updates. Thanks for shopping with TrustedElectronics!",
    order_delivered: "Order delivered successfully! Thank you for shopping with TrustedElectronics. Rate your experience!",
    promotion: "Special offer just for you! Get 20% off on electronics. Visit TrustedElectronics today. Limited time offer!"
};

function useTemplate(templateKey) {
    const template = templates[templateKey];
    if (template) {
        document.getElementById('message').value = template;
        document.getElementById('message').dispatchEvent(new Event('input'));
    }
}

function previewMessage() {
    const phoneNumbers = document.getElementById('phone_numbers').value.trim();
    const message = document.getElementById('message').value.trim();
    
    if (!phoneNumbers || !message) {
        alert('Please enter both phone numbers and message');
        return;
    }
    
    // Count recipients
    const recipients = phoneNumbers.split(/[\r\n,]+/).map(p => p.trim()).filter(p => p).length;
    
    // Show preview
    document.getElementById('messagePreview').textContent = message;
    document.getElementById('recipientCount').textContent = recipients;
    document.getElementById('previewSection').classList.remove('hidden');
}
</script>
@endsection