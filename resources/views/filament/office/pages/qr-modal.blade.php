<div class="p-4 space-y-4">
    <div class="text-center">
        <h2 class="text-lg font-bold mb-4">Evaluation Form QR Code</h2>
        
        <!-- QR Code Image -->
        <div class="mb-6">
            <img src="{{ $qrCodePath }}" alt="QR Code" class="mx-auto">
            <a href="{{ $qrCodePath }}" download="evaluation-qr-code.svg" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg mt-2 hover:bg-primary-700">
                <x-heroicon-s-arrow-down-tray class="w-4 h-4 mr-2"/>
                Download QR Code
            </a>
        </div>

        <!-- Direct Link -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-2">Direct Link:</label>
            <div class="flex items-center space-x-2">
                <input type="text"
                       value="{{ $evaluationUrl }}"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 text-gray-900"
                       readonly
                       x-ref="urlInput">
                <button x-on:click="
                        await navigator.clipboard.writeText($refs.urlInput.value);
                        $dispatch('notify', {
                            message: 'Link copied to clipboard',
                            status: 'success'
                        });
                    "
                        class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">
                    <x-heroicon-s-clipboard class="w-4 h-4 mr-2"/>
                    Copy
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.qr-code-container img {
    max-width: 300px;
    height: auto;
}
</style>