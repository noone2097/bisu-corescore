<!-- Visitor Information and Signature -->
<div class="space-y-6">
    <h3 class="text-lg font-medium text-gray-900">Visitor Information</h3>
    
    <!-- Name Fields -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
            <input type="text" 
                   name="first_name" 
                   class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                   required 
                   placeholder="Enter your first name" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
            <input type="text" 
                   name="last_name" 
                   class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                   required 
                   placeholder="Enter your last name" />
        </div>
    </div>

    <!-- Digital Signature -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Digital Signature</label>
        <div id="signature-pad" class="border border-gray-300 rounded-md p-4 max-w-3xl mx-auto">
            <div class="signature-pad--body h-[150px] sm:h-[200px] relative bg-white overflow-hidden">
                <canvas id="signatureCanvas" class="absolute inset-0 w-full h-full rounded-md"></canvas>
            </div>
            <input type="hidden" name="signature" id="signature" required />
            <div class="mt-2 flex justify-between items-center">
                <p class="text-sm text-gray-500">Please sign above using your mouse or touch screen</p>
                <button type="button" 
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md hover:bg-gray-50"
                        data-action="clear">
                    Clear
                </button>
            </div>
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
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        var rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        
        var isMobile = window.matchMedia("(max-width: 640px)").matches;
        canvas.getContext("2d").lineWidth = isMobile ? 1.5 : 2;

        // Clear and redraw if there's existing data
        if (signaturePad) {
            var data = signaturePad.toData();
            signaturePad.clear();
            signaturePad.fromData(data);
        }
    }

    function initPad() {
        var isMobile = window.matchMedia("(max-width: 640px)").matches;
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: isMobile ? 0.75 : 1,
            maxWidth: isMobile ? 2 : 2.5,
            throttle: isMobile ? 16 : 0,
            velocityFilterWeight: isMobile ? 0.5 : 0.7
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
                alert('Please provide your signature before submitting.');
                return;
            }
            document.getElementById('signature').value = signaturePad.toDataURL();
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