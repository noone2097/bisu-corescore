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
            <div x-data="{
                search: '',
                open: false,
                regions: [
                    'Region 1 - Ilocos Region',
                    'Region 2 - Cagayan Valley',
                    'Region 3 - Central Luzon',
                    'Region 4-A - CALABARZON',
                    'MIMAROPA Region',
                    'Region 5 - Bicol Region',
                    'Region 6 - Western Visayas',
                    'Region 7 - Central Visayas',
                    'Region 8 - Eastern Visayas',
                    'Region 9 - Zamboanga Peninsula',
                    'Region 10 - Northern Mindanao',
                    'Region 11 - Davao Region',
                    'Region 12 - SOCCSKSARGEN',
                    'Region 13 - Caraga',
                    'NCR - National Capital Region',
                    'CAR - Cordillera Administrative Region',
                    'BARMM - Bangsamoro Autonomous Region in Muslim Mindanao',
                    'NIR - Negros Island Region'
                ],
                filteredRegions() {
                    return this.regions.filter(region =>
                        region.toLowerCase().includes(this.search.toLowerCase())
                    )
                }
            }" class="relative">
                <input type="text"
                       name="region_of_residence"
                       x-model="search"
                       @click="open = true"
                       @click.away="open = false"
                       @focus="scrollToInput($el)"
                       class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                       placeholder="Search region..."
                       required
                       autocomplete="off" />
                
                <div x-show="open"
                     x-transition
                     class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    <template x-for="region in filteredRegions()" :key="region">
                        <div @click="search = region; open = false"
                             class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                             x-text="region">
                        </div>
                    </template>
                </div>
            </div>
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