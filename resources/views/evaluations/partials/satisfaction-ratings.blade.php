<!-- Satisfaction Ratings -->
<div class="space-y-6">
    <h3 class="text-lg font-medium text-gray-900">Satisfaction Ratings</h3>

    <!-- Instructions Box -->
    <div class="bg-blue-50 p-3 md:p-4 rounded-lg">
        <p class="text-xs md:text-sm text-gray-700">
            <strong>Instructions:</strong> For items 1-8, please put a check mark (✓) on the column that best corresponds to your answer.
        </p>
    </div>

    <!-- Ratings Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-2 py-1 md:px-4 md:py-2 text-left text-xs md:text-sm font-medium text-gray-700 border border-gray-300">No.</th>
                    <th class="px-2 py-1 md:px-4 md:py-2 text-left text-xs md:text-sm font-medium text-gray-700 border border-gray-300">Dimensions/Level of Satisfaction</th>
                    <th class="px-2 py-1 md:px-4 md:py-2 text-center text-xs md:text-sm font-medium text-gray-700 border border-gray-300">
                        Very Satisfied<br>
                        <span class="text-xs">(Nakontento pag-ayo)</span><br>
                        👍
                    </th>
                    <th class="px-2 py-1 md:px-4 md:py-2 text-center text-xs md:text-sm font-medium text-gray-700 border border-gray-300">
                        Satisfied<br>
                        <span class="text-xs">(Nakontento)</span><br>
                        😊
                    </th>
                    <th class="px-2 py-1 md:px-4 md:py-2 text-center text-xs md:text-sm font-medium text-gray-700 border border-gray-300">
                        Neither<br>
                        <span class="text-xs">(Neutral)</span><br>
                        😐
                    </th>
                    <th class="px-2 py-1 md:px-4 md:py-2 text-center text-xs md:text-sm font-medium text-gray-700 border border-gray-300">
                        Dissatisfied<br>
                        <span class="text-xs">(Wala nakontento)</span><br>
                        😕
                    </th>
                    <th class="px-2 py-1 md:px-4 md:py-2 text-center text-xs md:text-sm font-medium text-gray-700 border border-gray-300">
                        Very Dissatisfied<br>
                        <span class="text-xs">(Wala gayod nakontento)</span><br>
                        👎
                    </th>
                    <th class="px-2 py-1 md:px-4 md:py-2 text-center text-xs md:text-sm font-medium text-gray-700 border border-gray-300">
                        Not Applicable<br>
                        <span class="text-xs">(Walay mahitungod)</span><br>
                        🚫
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $ratings = [
                        ['field' => 'responsiveness', 'label' => 'Responsiveness (Pag abi-abi)'],
                        ['field' => 'reliability', 'label' => 'Reliability (Quality) (Masalign sa serbisyo)'],
                        ['field' => 'access_facilities', 'label' => 'Access & Facilities (Sayon tuoron ang opisina, komportable ug maayo ang mga pasilidad)'],
                        ['field' => 'communication', 'label' => 'Communication (Pamagd sa pag pasabot)'],
                        ['field' => 'costs', 'label' => 'Costs (Kantidad sa balayrunon)'],
                        ['field' => 'integrity', 'label' => 'Integrity (Matinud-anun, makiangayon,ug patas)'],
                        ['field' => 'assurance', 'label' => 'Assurance (Kapaniguruan sa serbisyo)'],
                        ['field' => 'outcome', 'label' => 'Outcome (Naangkon ang husto nga serbisyo)']
                    ];
                @endphp

                @foreach($ratings as $index => $rating)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-1 md:px-4 md:py-2 text-xs md:text-sm text-gray-700 border border-gray-300">{{ $index + 1 }}</td>
                        <td class="px-2 py-1 md:px-4 md:py-2 text-xs md:text-sm text-gray-700 border border-gray-300">{{ $rating['label'] }}</td>
                        @for($score = 5; $score >= 0; $score--)
                            <td class="px-2 py-1 md:px-4 md:py-2 text-center border border-gray-300">
                                <label class="custom-radio flex justify-center items-center">
                                    <input type="radio" 
                                           name="{{ $rating['field'] }}" 
                                           value="{{ $score }}" 
                                           class="hidden" 
                                           required />
                                    <span class="custom-radio-checkmark"></span>
                                </label>
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>