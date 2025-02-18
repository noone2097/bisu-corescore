<style>
    input[type="radio"]:checked + .custom-radio-checkmark {
        border-color: #3b82f6;
        background-color: #3b82f6;
    }
    input[type="radio"]:checked + .custom-radio-checkmark:after {
        background-color: white;
    }
</style>

<!-- CC Questions -->
<div class="space-y-8">
    <h3 class="text-lg font-medium text-gray-900">Citizen's Charter Questions</h3>

    <!-- Instructions Box -->
    <div class="bg-blue-50 p-3 rounded text-sm text-gray-700">
        <strong>Instructions:</strong> Check mark (✓) your answer to the Citizen's Charter (CC) questions. The Citizen's Charter is an official document that reflects the services of a government agency/office.
    </div>

    <!-- Questions Grid -->
    <div class="grid md:grid-cols-3 gap-8">
        <!-- CC1 Question -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">CC1: Which best describes your awareness of a CC? <span class="text-red-500">*</span></label>
            <div class="space-y-2">
                @php
                    $cc1Options = [
                        1 => "I know what a CC is and I saw this office's CC.",
                        2 => "I know what a CC is but I did not see its office's CC.",
                        3 => "I learned of the CC only when I saw this office's CC.",
                        4 => "I do not know what a CC is and I did not see one in this office. (Anwser 'N/A' on CC2 and CC3)",
                    ];
                @endphp

                @foreach($cc1Options as $value => $label)
                    <label class="flex items-start cursor-pointer group">
                        <span class="custom-radio mt-0.5">
                            <input type="radio" name="cc1" value="{{ $value }}" class="hidden" required />
                            <span class="custom-radio-checkmark"></span>
                        </span>
                        <span class="text-sm text-gray-600 ml-2 group-hover:text-gray-900">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- CC2 Question -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">CC2: If aware of CC, would you say it was...? <span class="text-red-500">*</span></label>
            <div class="space-y-2">
                @php
                    $cc2Options = [
                        1 => "Easy to see",
                        2 => "Somewhat easy to see",
                        3 => "Difficult to see",
                        4 => "Not visible at all",
                        5 => "N/A"
                    ];
                @endphp

                @foreach($cc2Options as $value => $label)
                    <label class="flex items-start cursor-pointer group">
                        <span class="custom-radio mt-0.5">
                            <input type="radio" name="cc2" value="{{ $value }}" class="hidden" required />
                            <span class="custom-radio-checkmark"></span>
                        </span>
                        <span class="text-sm text-gray-600 ml-2 group-hover:text-gray-900">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- CC3 Question -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">CC3: How much did the CC help you? <span class="text-red-500">*</span></label>
            <div class="space-y-2">
                @php
                    $cc3Options = [
                        1 => "Helped very much",
                        2 => "Somewhat helped",
                        3 => "Did not help",
                        4 => "N/A"
                    ];
                @endphp

                @foreach($cc3Options as $value => $label)
                    <label class="flex items-start cursor-pointer group">
                        <span class="custom-radio mt-0.5">
                            <input type="radio" name="cc3" value="{{ $value }}" class="hidden" required />
                            <span class="custom-radio-checkmark"></span>
                        </span>
                        <span class="text-sm text-gray-600 ml-2 group-hover:text-gray-900">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all CC1 radio buttons and N/A options
        const cc1Radios = document.querySelectorAll('input[name="cc1"]');
        const cc2NA = document.querySelector('input[name="cc2"][value="5"]');
        const cc3NA = document.querySelector('input[name="cc3"][value="4"]');
        const cc2Options = document.querySelectorAll('input[name="cc2"]');
        const cc3Options = document.querySelectorAll('input[name="cc3"]');

        // Function to handle N/A options state
        function handleNAOptions(enableNA) {
            if (enableNA) {
                cc2NA.disabled = false;
                cc3NA.disabled = false;
                cc2NA.checked = true;
                cc3NA.checked = true;
            } else {
                cc2NA.disabled = true;
                cc3NA.disabled = true;
                cc2NA.checked = false;
                cc3NA.checked = false;
            }
        }

        // Disable N/A options initially
        handleNAOptions(false);

        // Add change event listener to CC1 radio buttons
        cc1Radios.forEach(radio => {
            radio.addEventListener('change', function() {
                handleNAOptions(this.value === '4');

                // Add event listeners to CC2 and CC3 options
                cc2Options.forEach(option => {
                    if (option !== cc2NA) {
                        option.disabled = this.value === '4';
                    }
                });

                cc3Options.forEach(option => {
                    if (option !== cc3NA) {
                        option.disabled = this.value === '4';
                    }
                });
            });
        });

        // Add event listeners to prevent selecting N/A when CC1 is not option 4
        [cc2NA, cc3NA].forEach(na => {
            na.addEventListener('click', function(event) {
                const cc1Selected = Array.from(cc1Radios).find(radio => radio.checked);
                if (!cc1Selected || cc1Selected.value !== '4') {
                    event.preventDefault();
                    this.checked = false;
                }
            });
        });
    });
</script>