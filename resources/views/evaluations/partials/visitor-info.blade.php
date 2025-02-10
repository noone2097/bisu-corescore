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
        <div class="border border-gray-300 rounded-md p-4 max-w-3xl mx-auto">
            <canvas id="signatureCanvas" width="600" height="200" class="w-full border border-gray-200 rounded-md cursor-crosshair touch-none"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
<script>
var canvas = document.getElementById('signatureCanvas');
var signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255, 255, 255)',
    penColor: 'rgb(0, 0, 0)',
    minWidth: 2,
    maxWidth: 4
});

function clearSignature() {
    signaturePad.clear();
    document.getElementById('signature').value = '';
}

// Handle form submission
document.getElementById('evaluationForm').addEventListener('submit', function(e) {
    if (signaturePad.isEmpty()) {
        e.preventDefault();
        alert('Please provide your signature before submitting.');
        return;
    }
    document.getElementById('signature').value = signaturePad.toDataURL();
});

// Prevent scrolling on touch devices
canvas.addEventListener('touchstart', function(e) {
    e.preventDefault();
}, { passive: false });

canvas.addEventListener('touchmove', function(e) {
    e.preventDefault();
}, { passive: false });
</script>