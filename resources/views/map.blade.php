<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BIMS — Bangladesh Incident Map</title>
    @vite(['resources/js/app.js', 'resources/css/map.css'])
</head>

<body>
    <div id="summary-widget">
        <div class="summary-card">
            <div class="icon">📌</div>
            <div class="text">
                <div class="number" id="total-incidents">0</div>
                <div class="label">Total Incidents</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon">🔥</div>
            <div class="text">
                <div class="number" id="max-severity">0</div>
                <div class="label">Max Severity</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="icon">🗓️</div>
            <div class="text">
                <div class="number" id="recent-incidents">0</div>
                <div class="label">Recent Incidents</div>
            </div>
        </div>
    </div>

    <div id="container">
        <div id="map"></div>

        <div id="controls">
            <label>Severity
                <select id="filter-severity">
                    <option value="">All</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </label>
            <label>Division
                <input type="text" id="filter-division" placeholder="Dhaka">
            </label>
            <label>District
                <input type="text" id="filter-district" placeholder="Dhaka">
            </label>
            <label>Category
                <input type="text" id="filter-category" placeholder="Violence">
            </label>
            <label>Sort
                <select id="filter-sort">
                    <option value="latest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="severity_high">Severity High → Low</option>
                    <option value="severity_low">Severity Low → High</option>
                </select>
            </label>
            <button id="apply-filters">Apply</button>
        </div>
        
        <!-- Legend -->
        <div id="map-legend">
            <h4>Incident Types</h4>
            <div>🔥 Violence</div>
            <div>🛡️ Theft</div>
            <div>⚠️ Accident</div>
            <div>✊ Protest</div>
        </div>

    </div>


    
</body>

</html>