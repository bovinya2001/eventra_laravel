<script>
    (() => {
        const select = document.getElementById('location_key');
        const map = document.getElementById('venue-map');

        const updateMap = () => {
            const option = select.options[select.selectedIndex];
            const lat = Number(option?.dataset.lat);
            const lng = Number(option?.dataset.lng);

            if (!lat || !lng) {
                map.classList.add('hidden');
                map.removeAttribute('src');
                return;
            }

            const offset = 0.025;
            const bbox = [lng - offset, lat - offset, lng + offset, lat + offset].join(',');
            const params = new URLSearchParams({ bbox, layer: 'mapnik', marker: `${lat},${lng}` });
            map.src = `https://www.openstreetmap.org/export/embed.html?${params}`;
            map.classList.remove('hidden');
        };

        select.addEventListener('change', updateMap);
        updateMap();
    })();
</script>
