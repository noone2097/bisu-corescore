<!-- Date and Time of Visit -->
<div class="space-y-6">
    <h3 class="text-lg font-medium text-gray-900">Visit Information</h3>
    
    <div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Date of Visit<br>
                    <span class="font-normal">(Petsa sa Pagbisita)</span>
                </label>
                <input type="date"
                        name="date_of_visit"
                        class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md bg-gray-100 appearance-none [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-inner-spin-button]:hidden [&::-webkit-outer-spin-button]:hidden [-moz-appearance:textfield]"
                        value="{{ date('Y-m-d') }}"
                        required
                        readonly />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Time of Visit<br>
                    <span class="font-normal">(Oras sa Pagbisita)</span>
                </label>
                <input type="time"
                        name="time_of_visit"
                        class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md bg-gray-100 appearance-none [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-inner-spin-button]:hidden [&::-webkit-outer-spin-button]:hidden [-moz-appearance:textfield]"
                        value="{{ date('H:i') }}"
                        required
                        readonly />
            </div>
        </div>
    </div>

    <!-- Unit/Office Visited and Client Type -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Unit/Office Visited (Gibisita nga Opisina) <span class="text-red-500">*</span></label>
            @if($isOfficeLocked)
                <input type="hidden" name="office_id" value="{{ $office->id }}">
                <input type="text"
                    class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md bg-gray-100"
                    value="{{ $office->name }}"
                    readonly>
            @else
                <select name="office_id"
                     class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                     required>
                    <option value="">Select Office</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
            @endif
            <p class="mt-2 text-sm text-gray-500 italic">Note: Unit/Office visited, current date and time are automatically set</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Client Type (Klasi sa Bisita) <span class="text-red-500">*</span></label>
            <select name="client_type" 
                    class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                    required>
                <option value="">Select Client Type</option>
                <option value="Citizen">Citizen</option>
                <option value="Business">Business</option>
                <option value="Government">Government (Employee or another Agency)</option>
            </select>
        </div>
    </div>
</div>