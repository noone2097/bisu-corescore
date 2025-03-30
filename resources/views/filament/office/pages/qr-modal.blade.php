<div class="py-12 px-8">
    <div class="text-center">
        <!-- QR Code -->
        <div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg inline-block shadow-sm">
                <img src="{{ $qrCodePath }}" alt="QR Code" class="w-48 h-48">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-10 mb-16">
            <div class="flex gap-3 justify-center pb-4">
                <a href="{{ $qrCodePath }}"
                    download="feedback-qr-code.svg"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 transition-colors duration-150 shadow-sm">
                    <x-heroicon-s-arrow-down-tray class="w-4 h-4 mr-1.5"/>
                    Download QR
                </a>
                @php
                    $qrPdfUrl = route('feedback.qr.pdf', ['qrCodePath' => 'qr-codes/' . basename($qrCodePath)]);
                @endphp
                <a href="{{ $qrPdfUrl }}"
                    target="_blank"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 transition-colors duration-150 shadow-sm">
                    <x-heroicon-s-printer class="w-4 h-4 mr-1.5"/>
                    Print PDF
                </a>
            </div>
        </div>

        <!-- Direct Link -->
        <div class="max-w-sm mx-auto pt-4" x-data="{ isCheck: false }">
            <div class="flex items-center gap-2">
                <input type="text"
                       value="{{ $feedbackUrl }}"
                       class="w-full px-3 py-1.5 text-xs bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-white rounded-md shadow-sm focus:ring-1 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-primary-500 dark:focus:border-primary-400"
                       readonly
                       x-ref="urlInput">
                <button type="button"
                        @click="
                            navigator.clipboard.writeText($refs.urlInput.value);
                            isCheck = true;
                            setTimeout(() => isCheck = false,3000);"
                        class="inline-flex items-center justify-center px-2 py-1 text-xs bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-750 shadow-sm transition-all duration-150">
                    <template x-if="!isCheck">
                        <x-heroicon-s-clipboard class="w-5 h-5 text-gray-600 dark:text-white"/>
                    </template>
                    <template x-if="isCheck">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#22c55e">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                    </template>
                </button>
            </div>
        </div>
    </div>
</div>