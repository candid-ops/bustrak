<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BusTrak — Live Bus Tracking</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background: #0B0F1A;
        font-family: 'Space Grotesk', sans-serif;
        height: 100vh;
        color: #fff;
        overflow: hidden;
    }
    
    #map {
        height: 100vh;
        width: 100%;
        z-index: 1;
    }
    
    /* Header */
    .header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: rgba(11, 15, 26, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(26, 77, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        height: 70px;
    }
    
    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .logo-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #1A4DFF, #0033cc);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 4px 15px rgba(26, 77, 255, 0.3);
    }
    
    .logo-text {
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: 1px;
        font-family: 'Space Grotesk', sans-serif;
    }
    
    .logo-text span {
        color: #FF8C42;
    }
    
    .live-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(0, 200, 100, 0.15);
        border: 1px solid rgba(0, 200, 100, 0.4);
        border-radius: 50px;
        padding: 8px 16px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #00c864;
        letter-spacing: 1px;
    }
    
    .live-dot {
        width: 10px;
        height: 10px;
        background: #00c864;
        border-radius: 50%;
        animation: pulse 1.5s ease-in-out infinite;
        box-shadow: 0 0 10px #00c864;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }
    
    /* Map type buttons */
    .map-buttons {
        display: flex;
        gap: 12px;
        background: rgba(11, 15, 26, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 6px;
        border: 1px solid rgba(26, 77, 255, 0.2);
    }
    
    .map-btn {
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.6);
        font-family: 'Space Grotesk', sans-serif;
    }
    
    .map-btn.active {
        background: linear-gradient(135deg, #1A4DFF, #0033cc);
        color: white;
        box-shadow: 0 4px 15px rgba(26, 77, 255, 0.4);
    }
    
    .map-btn:hover:not(.active) {
        background: rgba(26, 77, 255, 0.2);
        color: white;
    }
    
    /* Back button */
    .back-btn {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        padding: 8px 18px;
        color: white;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }
    
    .back-btn:hover {
        background: rgba(26, 77, 255, 0.2);
        border-color: rgba(26, 77, 255, 0.4);
        transform: translateY(-2px);
    }
    
    /* Info Panel */
    .info-panel {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 1000;
        background: rgba(11, 15, 26, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 12px 20px;
        border: 1px solid rgba(26, 77, 255, 0.3);
        font-size: 0.8rem;
        display: flex;
        flex-direction: column;
        gap: 8px;
        pointer-events: none;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .info-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .info-dot.blue {
        background: #1A4DFF;
        box-shadow: 0 0 8px #1A4DFF;
    }
    
    .info-dot.orange {
        background: #FF8C42;
        box-shadow: 0 0 8px #FF8C42;
    }
    
    /* Bus Marker */
    .bus-marker {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #1A4DFF, #0033cc);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 0 20px rgba(26, 77, 255, 0.8);
        border: 2px solid rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .bus-marker:hover {
        transform: scale(1.1);
    }
    
    .bus-marker.orange {
        background: linear-gradient(135deg, #FF8C42, #e06000);
        box-shadow: 0 0 20px rgba(255, 140, 66, 0.8);
    }
    
    /* Popup */
    .popup-card {
        padding: 12px;
        min-width: 220px;
    }
    
    .popup-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #1A4DFF;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .popup-info {
        font-size: 0.8rem;
        color: #ccc;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .popup-info i {
        width: 20px;
        color: #FF8C42;
    }
    
    .leaflet-popup-content-wrapper {
        background: rgba(11, 15, 26, 0.98) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(26, 77, 255, 0.4) !important;
        border-radius: 16px !important;
        color: white !important;
    }
    
    .leaflet-popup-tip {
        background: rgba(11, 15, 26, 0.98) !important;
    }
    
    .leaflet-control-attribution {
        background: rgba(0, 0, 0, 0.6) !important;
        color: rgba(255, 255, 255, 0.3) !important;
        font-size: 0.6rem !important;
        padding: 2px 6px !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .header {
            padding: 0 16px;
            height: 60px;
        }
        
        .logo-text {
            font-size: 0.9rem;
        }
        
        .logo-icon {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
        }
        
        .map-btn {
            padding: 5px 12px;
            font-size: 0.7rem;
        }
        
        .live-badge {
            padding: 5px 10px;
            font-size: 0.65rem;
        }
        
        .back-btn {
            padding: 5px 12px;
            font-size: 0.7rem;
        }
        
        .info-panel {
            bottom: 10px;
            left: 10px;
            padding: 8px 12px;
            font-size: 0.7rem;
        }
    }
</style>
</head>
<body>

<div id="map"></div>

<!-- Header -->
<div class="header">
    <div class="logo">
        <div class="logo-icon">🚌</div>
        <div class="logo-text">Bus<span>Trak</span></div>
    </div>
    
    <div class="map-buttons">
        <button class="map-btn active" onclick="setMapType('satellite')">🛰️ Satellite</button>
        <button class="map-btn" onclick="setMapType('street')">🗺️ Street</button>
    </div>
    
    <div class="live-badge">
        <span class="live-dot"></span>
        LIVE TRACKING
    </div>
    
    @auth
    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : (auth()->user()->hasRole('driver') ? route('driver.dashboard') : route('customer.dashboard')) }}" 
       class="back-btn">
        <i class="bi bi-arrow-left"></i> Dashboard
    </a>
    @else
    <a href="{{ route('welcome') }}" class="back-btn">
        <i class="bi bi-house"></i> Home
    </a>
    @endauth
</div>

<!-- Info Panel -->
<div class="info-panel">
    <div class="info-item">
        <div class="info-dot blue"></div>
        <span>Bus KAA 123A (Nairobi → Mombasa)</span>
    </div>
    <div class="info-item">
        <div class="info-dot orange"></div>
        <span>Bus KCD 456B (Nairobi CBD → Westlands)</span>
    </div>
    <div class="info-item">
        <i class="bi bi-clock" style="color: #00c864;"></i>
        <span>Updating every 3 seconds</span>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Real map layers
    const layers = {
        // Real Satellite Imagery
        satellite: L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '© Google',
            maxZoom: 20
        }),
        // Real Street Map with Dark Theme
        street: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap © CARTO',
            maxZoom: 19
        }),
        // Alternative: Real Street Map (OpenStreetMap)
        osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19
        })
    };
    
    // Initialize map centered on Kenya
    const map = L.map('map').setView([-1.286389, 36.817223], 7);
    
    let currentLayer = 'satellite';
    layers.satellite.addTo(map);
    
    // Switch map type function
    window.setMapType = function(type) {
        if (currentLayer === type) return;
        
        map.removeLayer(layers[currentLayer]);
        
        if (type === 'satellite') {
            layers.satellite.addTo(map);
        } else if (type === 'street') {
            layers.osm.addTo(map);
        }
        
        currentLayer = type;
        
        // Update button styles
        document.querySelectorAll('.map-btn').forEach(btn => btn.classList.remove('active'));
        if (type === 'satellite') {
            document.querySelector('.map-btn:first-child').classList.add('active');
        } else {
            document.querySelector('.map-btn:last-child').classList.add('active');
        }
    };
    
    // Bus data from PHP with real coordinates (Kenyan routes)
    const busRoutes = @json($busRoutes);
    
    // Fallback data if PHP returns empty
    const defaultRoutes = [
        {
            id: 1,
            plate: 'KAA 123A',
            route: 'Nairobi → Mombasa',
            coords: [
                [-1.286389, 36.817223],  // Nairobi CBD
                [-1.2921, 36.8219],      // Nairobi Town
                [-1.3100, 36.8300],      // South B
                [-1.3582, 36.8642],      // Athi River
                [-1.4789, 36.9142],      // Machakos Turnoff
                [-1.6000, 37.0000],      // Emali
                [-1.8000, 37.1000],      // Kibwezi
                [-2.0000, 37.2000],      // Mtito Andei
                [-2.2000, 37.3000],      // Voi
                [-2.4000, 37.4000],      // Bachuma
                [-2.7000, 37.5000],      // Maungu
                [-3.0000, 37.6000],      // Mariakani
                [-3.2000, 37.7000],      // Bonje
                [-3.4000, 37.8000],      // Mazeras
                [-3.6000, 37.9000],      // Mlolongo
                [-3.8000, 38.0000],      // Changamwe
                [-4.0435, 39.6682]       // Mombasa
            ]
        },
        {
            id: 2,
            plate: 'KCD 456B',
            route: 'Nairobi CBD → Westlands',
            coords: [
                [-1.286389, 36.817223],  // CBD
                [-1.2750, 36.8200],      // Museum Hill
                [-1.2650, 36.8150],      // Westlands Roundabout
                [-1.2600, 36.8100],      // Westlands
                [-1.2550, 36.8050]       // Kabete
            ]
        }
    ];
    
    const routes = (busRoutes && busRoutes.length > 0) ? busRoutes : defaultRoutes;
    
    const markers = {};
    const positions = {};
    let currentPositions = {};
    
    // Custom bus icon creator
    function createBusIcon(index) {
        const isOrange = index === 1;
        return L.divIcon({
            className: '',
            html: `<div class="bus-marker ${isOrange ? 'orange' : ''}">🚌</div>`,
            iconSize: [42, 42],
            iconAnchor: [21, 21],
            popupAnchor: [0, -21]
        });
    }
    
    // Draw routes and place buses
    routes.forEach((bus, index) => {
        if (!bus.coords || bus.coords.length < 2) return;
        
        // Draw route line
        const routeColor = index === 0 ? '#1A4DFF' : '#FF8C42';
        const routeLine = L.polyline(bus.coords, {
            color: routeColor,
            weight: 4,
            opacity: 0.8,
            smoothFactor: 1
        }).addTo(map);
        
        // Place bus at first position
        positions[bus.id] = 0;
        currentPositions[bus.id] = bus.coords[0];
        
        markers[bus.id] = L.marker(bus.coords[0], {
            icon: createBusIcon(index)
        }).addTo(map).bindPopup(`
            <div class="popup-card">
                <div class="popup-title">
                    <i class="bi bi-bus-front"></i> ${bus.plate}
                </div>
                <div class="popup-info">
                    <i class="bi bi-signpost-2"></i> ${bus.route}
                </div>
                <div class="popup-info">
                    <i class="bi bi-clock"></i> Status: <span style="color: #00c864;">Active</span>
                </div>
                <div class="popup-info">
                    <i class="bi bi-geo-alt"></i> Real-time tracking
                </div>
            </div>
        `);
    });
    
    // Animate buses smoothly along the route
    let step = 0;
    function animateBuses() {
        routes.forEach((bus, index) => {
            if (!bus.coords || bus.coords.length < 2) return;
            
            let nextPos = positions[bus.id] + 1;
            if (nextPos >= bus.coords.length) {
                nextPos = 0;
            }
            positions[bus.id] = nextPos;
            
            // Smooth animation by moving slightly between points
            const currentCoord = bus.coords[positions[bus.id]];
            if (markers[bus.id]) {
                markers[bus.id].setLatLng(currentCoord);
                currentPositions[bus.id] = currentCoord;
            }
        });
    }
    
    // Move buses every 4 seconds for smooth tracking
    setInterval(animateBuses, 4000);
    
    // Add scale bar
    L.control.scale({ metric: true, imperial: false, position: 'bottomright' }).addTo(map);
    
    // Add zoom control
    map.zoomControl.setPosition('topright');
    
    console.log('Live map initialized with real-time tracking');
</script>
</body>
</html>