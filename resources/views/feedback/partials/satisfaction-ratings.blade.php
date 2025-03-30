<!-- Satisfaction Ratings -->
<div class="space-y-4">
    <h3 class="text-lg font-medium text-gray-900">Satisfaction Ratings</h3>

    <!-- Instructions Box -->
    <div class="bg-blue-50 p-3 rounded text-sm text-gray-700">
        <strong>Instructions:</strong> For items 1-8, please put a check mark (✓) on the column that best corresponds to your answer.
    </div>

    <!-- Ratings Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-2 py-1 text-left text-xs font-medium text-gray-700 border border-gray-300">No.</th>
                    <th class="px-3 py-1 text-left text-sm font-medium text-gray-700 border border-gray-300">Dimensions/Level of Satisfaction</th>
                    <th class="px-2 py-1 text-center text-xs font-medium text-gray-700 border border-gray-300">
                        Very Satisfied<br>
                        <span class="text-[10px] text-gray-500">(Nakontento pag-ayo)</span><br>
                        👍
                    </th>
                    <th class="px-2 py-1 text-center text-xs font-medium text-gray-700 border border-gray-300">
                        Satisfied<br>
                        <span class="text-[10px] text-gray-500">(Nakontento)</span><br>
                        😊
                    </th>
                    <th class="px-2 py-1 text-center text-xs font-medium text-gray-700 border border-gray-300">
                        Neither<br>
                        <span class="text-[10px] text-gray-500">(Neutral)</span><br>
                        😐
                    </th>
                    <th class="px-2 py-1 text-center text-xs font-medium text-gray-700 border border-gray-300">
                        Dissatisfied<br>
                        <span class="text-[10px] text-gray-500">(Wala nakontento)</span><br>
                        😕
                    </th>
                    <th class="px-2 py-1 text-center text-xs font-medium text-gray-700 border border-gray-300">
                        Very Dissatisfied<br>
                        <span class="text-[10px] text-gray-500">(Wala gayod nakontento)</span><br>
                        👎
                    </th>
                    <th class="px-2 py-1 text-center text-xs font-medium text-gray-700 border border-gray-300">
                        Not Applicable<br>
                        <span class="text-[10px] text-gray-500">(Walay mahitungod)</span><br>
                        🚫
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $ratings = [
                        ['field' => 'responsiveness', 'label' => 'Responsiveness', 'vernacular' => '(Pag abi-abi)'],
                        ['field' => 'reliability', 'label' => 'Reliability (Quality)', 'vernacular' => '(Masaligan sa serbisyo)'],
                        ['field' => 'access_facilities', 'label' => 'Access & Facilities', 'vernacular' => '(Sayon tuoron ang opisina, komportable ug maayo ang mga pasilidad)'],
                        ['field' => 'communication', 'label' => 'Communication', 'vernacular' => '(Pamaagi sa pag pasabot)'],
                        ['field' => 'costs', 'label' => 'Costs', 'vernacular' => '(Kantidad sa balayrunon)'],
                        ['field' => 'integrity', 'label' => 'Integrity', 'vernacular' => '(Matinud-anun, makiangayon,ug patas)'],
                        ['field' => 'assurance', 'label' => 'Assurance', 'vernacular' => '(Kapaniguruan sa serbisyo)'],
                        ['field' => 'outcome', 'label' => 'Outcome', 'vernacular' => '(Naangkon ang husto nga serbisyo)']
                    ];
                @endphp

                @foreach($ratings as $index => $rating)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-1 text-xs text-gray-700 border border-gray-300">{{ $index + 1 }}</td>
                        <td class="px-3 py-1 border border-gray-300">
                            <div class="text-sm font-medium text-gray-700">{{ $rating['label'] }} <span class="text-red-500">*</span></div>
                            <div class="text-[10px] text-gray-500">{{ $rating['vernacular'] }}</div>
                        </td>
                        @for($score = 5; $score >= 0; $score--)
                            <td class="px-1 py-2 text-center align-middle border border-gray-300">
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