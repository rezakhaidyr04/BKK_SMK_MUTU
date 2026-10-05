{{--
    Dependent dropdown Provinsi → Kabupaten/Kota → Kecamatan (seluruh Indonesia).
    Props: province, city, district (nilai terpilih), selectClass, labelClass,
    provincePlaceholder, cityPlaceholder, districtPlaceholder, showErrors.
    Kota dimuat via /wilayah/kota, kecamatan via /wilayah/kecamatan.
--}}
@props([
    'province' => null,
    'city' => null,
    'district' => null,
    'selectClass' => 'ui-select',
    'labelClass' => 'ui-label',
    'provincePlaceholder' => 'Pilih Provinsi',
    'cityPlaceholder' => 'Pilih Kabupaten/Kota',
    'districtPlaceholder' => 'Pilih Kecamatan',
    'showErrors' => true,
])
<div data-location-fields data-cities-url="{{ route('wilayah.cities') }}" data-districts-url="{{ route('wilayah.districts') }}" class="contents">
    <div>
        <label class="{{ $labelClass }}">Provinsi</label>
        <select name="province" data-province-select class="{{ $selectClass }}">
            <option value="">{{ $provincePlaceholder }}</option>
            @foreach(\App\Support\IndonesiaRegions::provinces() as $provinceName)
                <option value="{{ $provinceName }}" @selected($province == $provinceName)>{{ $provinceName }}</option>
            @endforeach
        </select>
        @if($showErrors)
            @error('province')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        @endif
    </div>
    <div>
        <label class="{{ $labelClass }}">Kabupaten/Kota</label>
        <select name="city" data-city-select data-selected-city="{{ $city }}" class="{{ $selectClass }}">
            <option value="">{{ $cityPlaceholder }}</option>
            @if($province && $city)
                <option value="{{ $city }}" selected>{{ $city }}</option>
            @endif
        </select>
        @if($showErrors)
            @error('city')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        @endif
    </div>
    <div>
        <label class="{{ $labelClass }}">Kecamatan</label>
        <select name="district" data-district-select data-selected-district="{{ $district }}" class="{{ $selectClass }}">
            <option value="">{{ $districtPlaceholder }}</option>
            @if($province && $city && $district)
                <option value="{{ $district }}" selected>{{ $district }}</option>
            @endif
        </select>
        @if($showErrors)
            @error('district')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        @endif
    </div>
</div>
<script>
(function () {
    document.querySelectorAll('[data-location-fields]').forEach(function (root) {
        if (root.dataset.bound === '1') return;
        root.dataset.bound = '1';
        var provinceSelect = root.querySelector('[data-province-select]');
        var citySelect = root.querySelector('[data-city-select]');
        var districtSelect = root.querySelector('[data-district-select]');
        var url = root.dataset.citiesUrl;
        var districtsUrl = root.dataset.districtsUrl;
        var placeholder = citySelect.querySelector('option[value=""]');
        var placeholderText = placeholder ? placeholder.textContent : 'Pilih Kabupaten/Kota';
        var districtPlaceholder = districtSelect ? districtSelect.querySelector('option[value=""]') : null;
        var districtPlaceholderText = districtPlaceholder ? districtPlaceholder.textContent : 'Pilih Kecamatan';

        function setDistrictOptions(districts, selected) {
            if (!districtSelect) return;
            districtSelect.innerHTML = '';
            var empty = document.createElement('option');
            empty.value = '';
            empty.textContent = districts.length ? districtPlaceholderText : 'Kecamatan tidak tersedia';
            districtSelect.appendChild(empty);
            districts.forEach(function (name) {
                var opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                if (selected && selected === name) opt.selected = true;
                districtSelect.appendChild(opt);
            });
        }

        function loadDistricts(province, city, selected) {
            if (!districtSelect) return Promise.resolve();
            if (!province || !city) {
                setDistrictOptions([], null);
                return Promise.resolve();
            }
            districtSelect.disabled = true;
            districtSelect.innerHTML = '';
            var loading = document.createElement('option');
            loading.value = '';
            loading.textContent = 'Memuat kecamatan…';
            districtSelect.appendChild(loading);
            return fetch(districtsUrl + '?province=' + encodeURIComponent(province) + '&city=' + encodeURIComponent(city), { headers: { 'Accept': 'application/json' } })
                .then(function (res) { return res.ok ? res.json() : []; })
                .then(function (districts) {
                    setDistrictOptions(Array.isArray(districts) ? districts : [], selected || null);
                    districtSelect.disabled = false;
                })
                .catch(function () {
                    setDistrictOptions([], null);
                    districtSelect.disabled = false;
                });
        }

        function setCityOptions(cities, selected) {
            citySelect.innerHTML = '';
            var empty = document.createElement('option');
            empty.value = '';
            empty.textContent = cities.length ? placeholderText : 'Kabupaten/kota tidak tersedia';
            citySelect.appendChild(empty);
            cities.forEach(function (name) {
                var opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                if (selected && selected === name) opt.selected = true;
                citySelect.appendChild(opt);
            });
        }

        function loadCities(province, selected) {
            if (!province) {
                setCityOptions([], null);
                return Promise.resolve();
            }
            citySelect.disabled = true;
            citySelect.innerHTML = '';
            var loading = document.createElement('option');
            loading.value = '';
            loading.textContent = 'Memuat kabupaten/kota…';
            citySelect.appendChild(loading);
            return fetch(url + '?province=' + encodeURIComponent(province), { headers: { 'Accept': 'application/json' } })
                .then(function (res) { return res.ok ? res.json() : []; })
                .then(function (cities) {
                    setCityOptions(Array.isArray(cities) ? cities : [], selected || null);
                    citySelect.disabled = false;
                })
                .catch(function () {
                    setCityOptions([], null);
                    citySelect.disabled = false;
                });
        }

        provinceSelect.addEventListener('change', function () {
            setDistrictOptions([], null);
            loadCities(provinceSelect.value, null);
        });

        citySelect.addEventListener('change', function () {
            loadDistricts(provinceSelect.value, citySelect.value, null);
        });

        if (provinceSelect.value) {
            loadCities(provinceSelect.value, citySelect.dataset.selectedCity || null).then(function () {
                if (citySelect.value) {
                    loadDistricts(provinceSelect.value, citySelect.value, districtSelect ? districtSelect.dataset.selectedDistrict || null : null);
                }
            });
        }
    });
})();
</script>
