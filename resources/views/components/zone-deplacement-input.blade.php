@props([
    'value' => '',
    'idPrefix' => 'zone',
    'label' => 'Zone d\'intervention / Rayon de déplacement'
])

@php
    $rawVal = old('zone_deplacement', $value ?? '');
    $distance = old('zone_distance', '');
    $unit = old('zone_unit', 'km');
    $precisions = old('zone_precisions', '');

    if ($rawVal && !$distance && !old('zone_unit')) {
        $trimmed = trim((string)$rawVal);
        if (preg_match('/^(\d+(?:[.,]\d+)?)\s*(km|mètres|metres|m)\b(?:\s*\(?(.*?)\)?)?$/i', $trimmed, $matches)) {
            $distance = str_replace(',', '.', $matches[1]);
            $u = strtolower($matches[2]);
            $unit = in_array($u, ['m', 'mètres', 'metres']) ? 'm' : 'km';
            $precisions = trim($matches[3] ?? '');
        } elseif (is_numeric($trimmed)) {
            $num = (float)$trimmed;
            if ($num > 100) {
                $distance = $num;
                $unit = 'm';
            } else {
                $distance = $num;
                $unit = 'km';
            }
        } elseif (stripos($trimmed, 'toute') !== false && stripos($trimmed, 'ville') !== false) {
            $unit = 'ville';
            $precisions = preg_replace('/^toute\s+la\s+ville\s*\(?/i', '', $trimmed);
            $precisions = trim($precisions, '() ');
        } elseif (stripos($trimmed, 'sans') !== false || stripos($trimmed, 'aucun') !== false) {
            $unit = 'aucun';
        } else {
            $unit = 'km';
            $precisions = $trimmed;
        }
    }
@endphp

<div class="space-y-1.5" id="{{ $idPrefix }}_container">
    <label class="text-sm font-semibold text-gray-700 block">
        {{ $label }}
    </label>

    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
        <!-- Distance -->
        <div class="sm:col-span-3" id="{{ $idPrefix }}_distance_col">
            <label class="text-xs font-medium text-gray-500 mb-1 block">Distance</label>
            <input type="number" step="any" min="0" 
                   id="{{ $idPrefix }}_distance" 
                   name="zone_distance" 
                   value="{{ $distance }}" 
                   placeholder="Ex: 10" 
                   class="w-full border-2 border-gray-100 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium" />
        </div>

        <!-- Unité -->
        <div class="sm:col-span-4" id="{{ $idPrefix }}_unit_col">
            <label class="text-xs font-medium text-gray-500 mb-1 block">Unité</label>
            <select id="{{ $idPrefix }}_unit" 
                    name="zone_unit" 
                    class="w-full border-2 border-gray-100 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium">
                <option value="km" {{ $unit === 'km' ? 'selected' : '' }}>km (Kilomètres)</option>
                <option value="m" {{ $unit === 'm' ? 'selected' : '' }}>m (Mètres)</option>
                <option value="ville" {{ $unit === 'ville' ? 'selected' : '' }}>Toute la ville</option>
                <option value="aucun" {{ $unit === 'aucun' ? 'selected' : '' }}>Sans déplacement</option>
            </select>
        </div>

        <!-- Précision géographique -->
        <div class="sm:col-span-5">
            <label class="text-xs font-medium text-gray-500 mb-1 block">Zone / Commune de référence</label>
            <input type="text" 
                   id="{{ $idPrefix }}_precisions" 
                   name="zone_precisions" 
                   value="{{ $precisions }}" 
                   placeholder="Ex: autour de Cocody, Abidjan..." 
                   class="w-full border-2 border-gray-100 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium" />
        </div>
    </div>

    <input type="hidden" name="zone_deplacement" id="{{ $idPrefix }}_final" value="{{ $rawVal }}" />
</div>

<script>
(function() {
    const distInput = document.getElementById('{{ $idPrefix }}_distance');
    const unitSelect = document.getElementById('{{ $idPrefix }}_unit');
    const precInput = document.getElementById('{{ $idPrefix }}_precisions');
    const finalInput = document.getElementById('{{ $idPrefix }}_final');
    const distCol = document.getElementById('{{ $idPrefix }}_distance_col');

    if (!distInput || !unitSelect || !precInput || !finalInput) return;

    function updateZone() {
        const unit = unitSelect.value;
        const dist = distInput.value.trim();
        const prec = precInput.value.trim();

        if (unit === 'ville') {
            distCol.style.opacity = '0.4';
            distInput.disabled = true;
            let res = 'Toute la ville';
            if (prec) res += ' (' + prec + ')';
            finalInput.value = res;
        } else if (unit === 'aucun') {
            distCol.style.opacity = '0.4';
            distInput.disabled = true;
            finalInput.value = 'Sans déplacement (en ligne / sur place)';
        } else {
            distCol.style.opacity = '1';
            distInput.disabled = false;
            if (dist !== '') {
                let res = dist + ' ' + unit;
                if (prec) res += ' (' + prec + ')';
                finalInput.value = res;
            } else if (prec) {
                finalInput.value = prec;
            } else {
                finalInput.value = '';
            }
        }
    }

    distInput.addEventListener('input', updateZone);
    unitSelect.addEventListener('change', updateZone);
    precInput.addEventListener('input', updateZone);

    updateZone();
})();
</script>
