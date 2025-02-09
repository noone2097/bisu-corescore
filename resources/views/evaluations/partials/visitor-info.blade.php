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
        <div class="border border-gray-300 rounded-md p-4">
            <canvas 
                id="signatureCanvas" 
                class="border border-gray-200 w-full bg-white rounded-md" 
                width="600" 
                height="200">
            </canvas>
            <input type="hidden" name="signature" id="signature" required />
            <div class="mt-2 flex justify-between items-center">
                <p class="text-sm text-gray-500">Please sign above using your mouse or touch screen</p>
                <button type="button" 
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md hover:bg-gray-50"
                        onclick="clearSignature()">
                    Clear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Signature Pad Script -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    let signaturePad;
    
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signatureCanvas');
        
        // Get canvas offset for coordinate adjustment
        const rect = canvas.getBoundingClientRect();
        const offsetX = rect.left;
        const offsetY = rect.top;
        
        signaturePad = new SignaturePad(canvas, {
            minWidth: 2,
            maxWidth: 4,
            penColor: "rgb(0, 0, 0)",
            backgroundColor: "rgb(255, 255, 255)",
            throttle: 0, // Remove throttling for better responsiveness
            velocityFilterWeight: 0.5, // Decrease velocity filtering for more accurate points
        });

        // Add coordinate adjustment for both mouse and touch events
        canvas.addEventListener('mousemove', function(e) {
            const point = {
                x: e.clientX - offsetX,
                y: e.clientY - offsetY
            };
            e._point = point;
        }, { passive: true });

        canvas.addEventListener('touchmove', function(e) {
            const touch = e.touches[0];
            const point = {
                x: touch.clientX - offsetX,
                y: touch.clientY - offsetY
            };
            e._point = point;
        }, { passive: false });

        // Prevent scrolling when touching the canvas
        canvas.addEventListener('touchstart', function(e) {
            e.preventDefault();
        });
        
        canvas.addEventListener('touchmove', function(e) {
            e.preventDefault();
        });
        
        canvas.addEventListener('touchend', function(e) {
            e.preventDefault();
        });

        // Update hidden input when signature changes
        signaturePad.addEventListener("endStroke", () => {
            document.getElementById('signature').value = signaturePad.toDataURL();
        });
    });

    function clearSignature() {
        signaturePad.clear();
        document.getElementById('signature').value = '';
    }

    // Form submission validation
    document.getElementById('evaluationForm').addEventListener('submit', function(e) {
        if (!document.getElementById('signature').value) {
            e.preventDefault();
            alert('Please provide your signature before submitting.');
        }
    });
</script>

<style>
    #signatureCanvas {
        touch-action: none;
    }
</style>