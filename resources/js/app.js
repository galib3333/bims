import './bootstrap';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import 'leaflet.heat';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet.markercluster';
window.L = L;

document.addEventListener('DOMContentLoaded', () => {
    const mapEl = document.getElementById('map');
    if (!mapEl) return;

    const bangladeshBounds = [
        [20.7, 88.0],
        [26.6, 92.7]
    ];

    // --- Map Initialization ---
    const map = L.map('map', {
        maxBounds: bangladeshBounds,
        minZoom: 7,
        maxZoom: 19,
        maxBoundsViscosity: 1.0
    }).setView([23.6850, 90.3563], 7);

    // --- Tile Layers ---
    const dayTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    });

    const nightTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    });

    // Start with night mode
    let nightMode = true;
    nightTiles.addTo(map);

    // --- Toggle Button ---
    const toggleBtn = document.createElement('button');
    toggleBtn.innerText = '☀️ Day Mode';
    toggleBtn.style.cssText = `
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 1000;
        padding: 8px 12px;
        border-radius: 6px;
        border: none;
        background: #e0e0e0;
        font-weight: bold;
        cursor: pointer;
    `;
    toggleBtn.addEventListener('click', () => {
        if (nightMode) {
            map.removeLayer(nightTiles);
            dayTiles.addTo(map);
            toggleBtn.innerText = '🌙 Night Mode';
        } else {
            map.removeLayer(dayTiles);
            nightTiles.addTo(map);
            toggleBtn.innerText = '☀️ Day Mode';
        }
        nightMode = !nightMode;
    });
    document.body.appendChild(toggleBtn);

    // --- Legend ---
    const legendDiv = document.createElement('div');
    legendDiv.id = 'map-legend';
    legendDiv.style.cssText = `
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        background: #2b2b2b;
        color: #fff;
        padding: 12px;
        border-radius: 8px;
        font-family: Arial, sans-serif;
        font-size: 13px;
        line-height: 1.5;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
        z-index: 9999;
    `;
    legendDiv.innerHTML = `
        <h4>Incident Types</h4>
        <div>🔥 Violence</div>
        <div>🛡️ Theft</div>
        <div>⚠️ Accident</div>
        <div>✊ Protest</div>
    `;
    document.body.appendChild(legendDiv);

    // --- Heatmap + Marker Cluster ---
    const heatLayerGroup = L.layerGroup().addTo(map);
    const markerClusterGroup = L.markerClusterGroup({
        showCoverageOnHover: false,
        maxClusterRadius: 40
    }).addTo(map);

    // --- Category Icons ---
    const categoryEmojis = {
        'Violence': '🔥',
        'Theft': '🛡️',
        'Accident': '⚠️',
        'Protest': '✊'
    };
    const getCategoryIcon = category => {
        const emoji = categoryEmojis[category] || '📌';
        return L.divIcon({
            html: `<div style="font-size: 24px;">${emoji}</div>`,
            className: '',
            iconSize: [24, 24],
            iconAnchor: [12, 24],
            popupAnchor: [0, -24]
        });
    };

    const getMarkerColor = severity => {
        if (severity <= 2) return '#00ff9f';
        if (severity <= 4) return '#ffaa00';
        return '#ff3e3e';
    };

    // --- Load Map Data ---
    function loadMapData(filters = {}) {
        fetch('/api/incidents/map?' + new URLSearchParams(filters))
            .then(res => res.json())
            .then(res => {
                if (res.status !== 'success') return;

                heatLayerGroup.clearLayers();
                markerClusterGroup.clearLayers();

                const points = res.data.map(i => [i.lat, i.lng, i.severity]);

                // Heatmap
                L.heatLayer(points, {
                    radius: 25,
                    blur: 15,
                    maxZoom: 17,
                    gradient: { 0.2: '#00ff9f', 0.5: '#ffaa00', 0.8: '#ff3e3e' }
                }).addTo(heatLayerGroup);

                // Markers
                res.data.forEach(i => {
                    const icon = getCategoryIcon(i.category);
                    const latOffset = (Math.random() - 0.5) * 0.0005;
                    const lngOffset = (Math.random() - 0.5) * 0.0005;

                    const marker = L.marker([parseFloat(i.lat) + latOffset, parseFloat(i.lng) + lngOffset], { icon })
                        .bindPopup(`<b>${i.title}</b><br>${i.category}<br>${i.occurred_at}`);
                    markerClusterGroup.addLayer(marker);
                });

                // --- Summary ---
                const total = res.data.length;
                let maxSeverity = 0;
                let recentCount = 0;
                const today = new Date();

                res.data.forEach(i => {
                    if (i.severity > maxSeverity) maxSeverity = i.severity;
                    const occurred = i.occurred_at ? new Date(i.occurred_at) : null;
                    if (occurred && (today - occurred)/(1000*60*60*24) <= 7) recentCount++;
                });

                document.getElementById('total-incidents').innerText = total;
                document.getElementById('max-severity').innerText = maxSeverity;
                document.getElementById('recent-incidents').innerText = recentCount;
            });
    }

    loadMapData();

    // --- Filter Form ---
    document.getElementById('apply-filters').addEventListener('click', () => {
        const filters = {
            severity: document.getElementById('filter-severity').value,
            division: document.getElementById('filter-division').value,
            district: document.getElementById('filter-district').value,
            category: document.getElementById('filter-category').value,
            sort: document.getElementById('filter-sort').value
        };
        loadMapData(filters);
    });
});
