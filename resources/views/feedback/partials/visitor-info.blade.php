    <!-- Visitor Information and Signature -->
    <div class="space-y-6" x-data="{ scrollToInput(el) { setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'center' }), 300) } }">
        <h3 class="text-lg font-medium text-gray-900">Visitor Information</h3>
        
        <!-- Name Fields -->
        <div class="space-y-6">
        <div class="flex flex-col items-center">
            <div class="w-[95%] sm:w-[90%]">
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                <input type="text"
                    name="first_name"
                    class="w-full px-3 py-2 md:px-4 md:py-3 border rounded-md focus:ring-2 focus:ring-blue-500 transition-all {{ $errors->has('first_name') ? 'border-red-500 bg-red-50' : 'border-gray-300' }}"
                    required
                    value="{{ old('first_name') }}"
                    placeholder="Enter your first name"
                    @focus="scrollToInput($el)"
                    required />
                @error('first_name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="flex flex-col items-center">
            <div class="w-[95%] sm:w-[90%]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                <input type="text"
                    name="last_name"
                    class="w-full px-3 py-2 md:px-4 md:py-3 border rounded-md focus:ring-2 focus:ring-blue-500 transition-all {{ $errors->has('last_name') ? 'border-red-500 bg-red-50' : 'border-gray-300' }}"
                    required
                    value="{{ old('last_name') }}"
                    placeholder="Enter your last name"
                    @focus="scrollToInput($el)"
                    required />
                @error('last_name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

        <!-- Digital Signature -->
        <div class="flex flex-col items-center">
            <div class="w-[95%] sm:w-[90%]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Digital Signature <span class="text-red-500">*</span></label>
                <div id="signature-pad" class="border {{ $errors->has('signature') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} rounded-md p-3 sm:p-4">
            <div class="signature-pad--body h-[120px] sm:h-[180px] relative bg-white overflow-hidden">
                <canvas id="signatureCanvas" class="absolute inset-0 w-full h-full rounded-md"></canvas>
            </div>
            <input type="hidden" name="signature" id="signature" required />
            <div class="mt-2 flex justify-between items-center space-x-4">
                <p class="text-sm text-gray-500">Please sign above using your mouse or touch screen</p>
                <button type="button" 
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md hover:bg-gray-50"
                        data-action="clear">
                    Clear
                </button>
            </div>
        </div>
    </div>

        <!-- Submit Button -->
        <div class="mt-8 text-center space-y-4">
            @if ($errors->any())
            <p class="text-sm text-red-600">
                Please fix the errors above before submitting the form
            </p>
            @endif
            <button type="submit"
                    x-bind:disabled="formSubmitting"
                    x-bind:class="{ 'opacity-50 cursor-not-allowed': formSubmitting }"
                    class="border border-blue-300 bg-blue-50 text-blue-700 hover:bg-blue-100 px-6 py-2.5 rounded-md transition-all duration-200 text-sm font-medium inline-flex items-center justify-center min-w-[180px]">
                <span x-text="formSubmitting ? 'Submitting...' : 'Submit'"></span>
                <svg x-show="formSubmitting" class="animate-spin ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>
</div>

<style>
.signature-pad--body canvas {
    cursor: crosshair;
    -ms-touch-action: none;
    touch-action: none;
    background: #fff;
    position: absolute !important;
    display: block;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
<script>
// Initialize signature pad immediately and handle visibility changes
function initSignaturePad() {
    var wrapper = document.getElementById("signature-pad");
    var canvas = document.getElementById("signatureCanvas");
    var signaturePad = null;
    var observer = null;

    function resizeCanvas() {
        // Get the device pixel ratio, falling back to 1
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        var rect = canvas.getBoundingClientRect();
        
        // Set a maximum size for the canvas to prevent large signature data
        // Get device width
        var isMobile = window.matchMedia("(max-width: 640px)").matches;
        var maxWidth = rect.width;
        var maxHeight = isMobile ? rect.height : Math.min(rect.height, 200);
        
        // Set canvas display size
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        
        // Set actual size in memory (scaled to account for extra pixel density)
        canvas.width = maxWidth * ratio;
        canvas.height = maxHeight * ratio;
        
        // Scale context to ensure correct drawing
        canvas.getContext("2d").scale(ratio, ratio);
        
        var isMobile = window.matchMedia("(max-width: 640px)").matches;
        canvas.getContext("2d").lineWidth = isMobile ? 1.5 : 2;

        // Clear and redraw if there's existing data
        if (signaturePad) {
            var data = signaturePad.toData();
            signaturePad.clear();
            if (data) {
                signaturePad.fromData(data);
            }
        }
    }

    function initPad() {
        var isMobile = window.matchMedia("(max-width: 640px)").matches;
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: isMobile ? 0.5 : 0.7,
            maxWidth: isMobile ? 1.5 : 2.0,
            throttle: isMobile ? 20 : 16,
            velocityFilterWeight: isMobile ? 0.6 : 0.4,
            dotSize: isMobile ? 1.5 : 2.0
        });

        // Update hidden input whenever signature changes
        signaturePad.addEventListener("endStroke", () => {
            if (!signaturePad.isEmpty()) {
                // Get signature with reduced quality and size
                const canvas = signaturePad.canvas;
                const ctx = canvas.getContext('2d');
                
                // Create a temporary canvas for resizing
                const tempCanvas = document.createElement('canvas');
                const tempCtx = tempCanvas.getContext('2d');
                
                // Set smaller dimensions (50% of original)
                tempCanvas.width = canvas.width * 0.5;
                tempCanvas.height = canvas.height * 0.5;
                
                // Draw original signature onto smaller canvas (this reduces the size)
                tempCtx.drawImage(canvas, 0, 0, tempCanvas.width, tempCanvas.height);
                
                // Convert to data URL with reduced quality
                const signatureData = tempCanvas.toDataURL('image/png', 0.5);
                document.getElementById('signature').value = signatureData;
                
                // Clear any previous error states
                document.getElementById('signature-pad').classList.remove('border-red-500', 'bg-red-50');
                const errorMessage = document.querySelector('#signature-error');
                if (errorMessage) {
                    errorMessage.remove();
                }
            }
        });

        // Handle visibility changes
        observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    requestAnimationFrame(resizeCanvas);
                }
            });
        }, { threshold: 0.1 });
        
        observer.observe(canvas);

        // Event Listeners
        window.addEventListener('resize', resizeCanvas);
        window.addEventListener('orientationchange', () => setTimeout(resizeCanvas, 300));

        document.querySelector('[data-action="clear"]').addEventListener("click", () => {
            signaturePad.clear();
            document.getElementById('signature').value = '';
        });

        canvas.addEventListener('touchstart', (e) => e.preventDefault(), { passive: false });
        canvas.addEventListener('touchmove', (e) => e.preventDefault(), { passive: false });

        document.getElementById('evaluationForm').addEventListener('submit', (e) => {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                const errorMessage = document.createElement('p');
                errorMessage.className = 'mt-2 text-sm text-red-600';
                errorMessage.textContent = 'Please provide your signature before submitting.';
                
                // Remove any existing error message
                const existingError = document.querySelector('#signature-error');
                if (existingError) {
                    existingError.remove();
                }
                
                // Add new error message
                errorMessage.id = 'signature-error';
                document.getElementById('signature-pad').appendChild(errorMessage);
                
                // Highlight the signature pad
                document.getElementById('signature-pad').classList.add('border-red-500', 'bg-red-50');
                
                return;
            }

            try {
                // Get the signature data with reduced quality
                const signatureData = signaturePad.toDataURL('image/png', 0.5); // 50% quality
                
                // Make sure we have valid signature data
                if (!signatureData.startsWith('data:image/png;base64,')) {
                    throw new Error('Invalid signature data format');
                }

                // Check signature data size (max 500KB)
                if (signatureData.length > 500000) {
                    throw new Error('Signature is too large. Please try again with a simpler signature.');
                }

                // Clear any error states if signature is valid
                document.getElementById('signature-pad').classList.remove('border-red-500', 'bg-red-50');
                const errorMessage = document.querySelector('#signature-error');
                if (errorMessage) {
                    errorMessage.remove();
                }

                // Set the signature value
                document.getElementById('signature').value = signatureData;
            } catch (error) {
                e.preventDefault();
                
                const errorMessage = document.createElement('p');
                errorMessage.className = 'mt-2 text-sm text-red-600';
                errorMessage.id = 'signature-error';
                errorMessage.textContent = error.message;
                
                // Remove any existing error message
                const existingError = document.querySelector('#signature-error');
                if (existingError) {
                    existingError.remove();
                }
                
                document.getElementById('signature-pad').appendChild(errorMessage);
                document.getElementById('signature-pad').classList.add('border-red-500', 'bg-red-50');
                return;
            }
        });
    }

    // Initialize immediately
    requestAnimationFrame(() => {
        resizeCanvas();
        initPad();
    });
}

// Start initialization when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSignaturePad);
} else {
    initSignaturePad();
}
</script>