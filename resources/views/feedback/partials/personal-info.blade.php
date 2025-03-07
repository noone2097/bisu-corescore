<!-- Personal Information -->
<div class="space-y-6" x-data="{ scrollToInput(el) { setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'center' }), 300) } }">
    <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>

    <!-- Sex and Region of Residence -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sex <span class="text-red-500">*</span></label>
            <select name="sex" 
                    class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                    required>
                <option value="">Select Sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Region of Residence <span class="text-red-500">*</span></label>
            <input type="text" 
                   name="region_of_residence" 
                   class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                   placeholder="Enter your region" 
                   required
                   @focus="scrollToInput($el)" />
        </div>
    </div>

    <!-- Services Availed and Served By -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Services Availed (Mga serbisyo nga nadawat) <span class="text-red-500">*</span></label>
            <input type="text" 
                   name="services_availed" 
                   class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                   placeholder="Enter services availed" 
                   required
                   @focus="scrollToInput($el)" />
           </div>
           <div>
               <label class="block text-sm font-medium text-gray-700 mb-2">Served By (Tawo nga naghatag sa serbisyo) <span class="text-red-500">*</span></label>
               <input type="text"
                      name="served_by"
                      class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                      placeholder="Enter name of server"
                      required
                      @focus="scrollToInput($el)" />
        </div>
    </div>
</div>