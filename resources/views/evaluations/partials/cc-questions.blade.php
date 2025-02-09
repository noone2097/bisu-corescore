<!-- CC Questions -->
<div class="space-y-6">
    <h3 class="text-lg font-medium text-gray-900">Citizen's Charter Questions</h3>

    <!-- Instructions Box -->
    <div class="bg-blue-50 p-3 md:p-4 rounded-lg">
        <p class="text-xs md:text-sm text-gray-700">
            <strong>Instructions:</strong> Check mark (✓) your answer to the Citizen's Charter (CC) questions. The Citizen's Charter is an official document that reflects the services of a government agency/office, including its requirements, fees, and processing time, among others.
        </p>
    </div>

    <!-- CC1 Question -->
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">CC1: Which of the following best describes your awareness of a CC?</label>
            <div class="space-y-2 md:space-y-3">
                @php
                    $cc1Options = [
                        1 => "I know what a CC is and I saw this office's CC.",
                        2 => "I know what a CC is but I did not see its office's CC.",
                        3 => "I learned of the CC only when I saw this office's CC.",
                        4 => "I do not know what a CC is and I did not see one in this office."
                    ];
                @endphp

                @foreach($cc1Options as $value => $label)
                    <label class="flex items-center space-x-2 md:space-x-3">
                        <span class="custom-radio">
                            <input type="radio" name="cc1" value="{{ $value }}" class="hidden" required />
                            <span class="custom-radio-checkmark"></span>
                        </span>
                        <span class="text-xs md:text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- CC2 Question -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">CC2: If aware of CC (answered 1-3 in CC1), would you say that the CC of this office was...?</label>
            <div class="space-y-2 md:space-y-3">
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
                    <label class="flex items-center space-x-2 md:space-x-3">
                        <span class="custom-radio">
                            <input type="radio" name="cc2" value="{{ $value }}" class="hidden" />
                            <span class="custom-radio-checkmark"></span>
                        </span>
                        <span class="text-xs md:text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- CC3 Question -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">CC3: If aware of CC (answered codes 1-3 in CC1), how much did the CC help you in your transaction?</label>
            <div class="space-y-2 md:space-y-3">
                @php
                    $cc3Options = [
                        1 => "Helped very much",
                        2 => "Somewhat helped",
                        3 => "Did not help",
                        4 => "N/A"
                    ];
                @endphp

                @foreach($cc3Options as $value => $label)
                    <label class="flex items-center space-x-2 md:space-x-3">
                        <span class="custom-radio">
                            <input type="radio" name="cc3" value="{{ $value }}" class="hidden" />
                            <span class="custom-radio-checkmark"></span>
                        </span>
                        <span class="text-xs md:text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</div>