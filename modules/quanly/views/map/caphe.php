<?php

use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\LeafletMap;
use app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset;
use app\widgets\maps\LeafletDrawAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;

// Đăng ký các tài nguyên của Yii2
LeafletMapAsset::register($this);
LeafletDrawAsset::register($this);
LeafletMeasureAsset::register($this);
LeafletLocateAsset::register($this);
?>

<!-- Tải các tài nguyên cần thiết -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
<script src="https://unpkg.com/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>


<style>
    #map {
        width: 100%;
        height: 600px;
        position: relative;
    }
    .legend.leaflet-control {
        background-color: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        max-height: 400px;
        overflow-y: auto;
    }
    .legend.hidden {
        display: none;
    }
    .legend h4 {
        margin-top: 0;
        margin-bottom: 5px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 3px;
    }
    .legend-item img {
        width: 20px;
        height: 20px;
        margin-right: 5px;
    }
    .popup-content table {
        width: 100%;
        border-collapse: collapse;
    }
    .popup-content th, .popup-content td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    .popup-content tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .modal-body iframe {
        width: 100%;
        height: calc(100vh - 58px); /* Full height minus header */
        border: none;
    }

    @media only screen and (max-width: 600px) {
        .legend.leaflet-control {
            max-height: 180px;
        }
    }
</style>

<div class="map-form">
    <div class="block block-themed">
        <div class="block-header">
            <h2 class="block-title"><?= 'Bản đồ đồi cà phê ' ?></h2>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-lg-12">
                    <div id="map"></div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // --- CÀI ĐẶT TRUNG TÂM ---
                            const WMS_URL = 'https://nongdanviet.net/geoserver/total_feeling/wms';
                            const WORKSPACE = 'total_feeling';
                            const center = [16.71055, 106.63144];

                            // --- KHỞI TẠO BẢN ĐỒ ---
                            const map = L.map('map', {
                                defaultExtentControl: true
                            }).setView(center, 18);

                            // --- CÁC LỚP BẢN ĐỒ NỀN ---
                            const baseMaps = {
                                "Bản đồ Google": L.tileLayer('https://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
                                    maxZoom: 22,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                }).addTo(map),
                                "Ảnh vệ tinh": L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                                    maxZoom: 22,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                })
                            };
                            
                            // --- CẤU HÌNH CÁC LỚP WMS ---
                            const wmsLayersConfig = [
                                { name: 'orthor_4326_chenhvenh', title: 'Ảnh nền', visible: true },
                                { name: 'dsm_4326_chenhvenh', title: 'Ảnh nền DSM', visible: false },
                                { name: '4326_ho_chua_nuoc', title: 'Hồ chứa nước', visible: false },
                                { name: '4326_ranh_chenhvenh', title: 'Ranh chênh vênh', visible: true },
                                { name: '4326_ddm_c_chenhvenh', title: 'Đường đồng mức', visible: false },
                                { name: '4326_caodo_chenhvenh', title: 'Cao độ chênh vênh', visible: false },
                                { name: '4326_coc_ranhdat', title: 'Cọc ranh đất', visible: false },
                                { name: '4326_cay_nganhoa', title: 'Cây ngân hoa', visible: true },
                                { name: '4326_cay_gaovang', title: 'Cây gáo vàng', visible: true },
                                { name: '4326_cay_chuoi', title: 'Cây chuối', visible: false },
                                { name: '4326_cay_caphe', title: 'Cây cà phê', visible: true },
                                { name: '4326_cay_sen_khac', title: 'Cây sưa', visible: false }
                            ];

                            const overlayMaps = {};
                            const wmsLayers = {};

                            wmsLayersConfig.forEach(config => {
                                const layer = L.tileLayer.wms(WMS_URL, {
                                    layers: `${WORKSPACE}:${config.name}`,
                                    format: 'image/png',
                                    transparent: true,
                                    maxZoom: 22
                                });
                                
                                wmsLayers[config.name] = layer;
                                overlayMaps[config.title] = layer;

                                if (config.visible) {
                                    layer.addTo(map);
                                }
                            });

                            // --- CÁC CÔNG CỤ ĐIỀU KHIỂN ---
                            L.control.locate({
                                position: 'topleft',
                                strings: { title: "Hiện vị trí", popup: "Bạn đang ở đây" },
                            }).addTo(map);

                            L.control.measure({
                                position: 'topleft',
                                primaryLengthUnit: 'meters',
                            }).addTo(map);

                            L.control.scale({ imperial: false, maxWidth: 150 }).addTo(map);

                            const highlightLayer = L.featureGroup().addTo(map);
                            overlayMaps["Highlight"] = highlightLayer;

                            L.control.layers(baseMaps, overlayMaps).addTo(map);

                            // --- THÊM NÚT MỞ 3D VIEWER ---
                            const view3DControl = L.control({ position: 'topleft' });
                            view3DControl.onAdd = function (map) {
                                const button = L.DomUtil.create('button', 'leaflet-bar leaflet-control');
                                button.innerHTML = '<i class="fa-solid fa-cube"></i>';
                                button.title = 'Xem mô hình 3D';
                                button.style.backgroundColor = 'white';
                                button.style.width = '30px';
                                button.style.height = '30px';
                                button.setAttribute('data-bs-toggle', 'modal');
                                button.setAttribute('data-bs-target', '#modal-3d-viewer');
                                return button;
                            };
                            view3DControl.addTo(map);


                            // --- XỬ LÝ SỰ KIỆN CLICK (GETFEATUREINFO) TỐI ƯU HÓA ---
                            map.on('click', function (e) {
                                const visibleLayers = wmsLayersConfig
                                    .filter(config => map.hasLayer(wmsLayers[config.name]))
                                    .map(config => `${WORKSPACE}:${config.name}`);

                                if (visibleLayers.length === 0) return;

                                const size = map.getSize();
                                const bbox = map.getBounds().toBBoxString();
                                const point = map.latLngToContainerPoint(e.latlng, map.getZoom());
                                
                                const params = {
                                    SERVICE: 'WMS',
                                    VERSION: '1.1.1',
                                    REQUEST: 'GetFeatureInfo',
                                    LAYERS: visibleLayers.join(','),
                                    QUERY_LAYERS: visibleLayers.join(','),
                                    BBOX: bbox,
                                    FEATURE_COUNT: 10,
                                    HEIGHT: size.y,
                                    WIDTH: size.x,
                                    FORMAT: 'image/png',
                                    INFO_FORMAT: 'application/json',
                                    SRS: 'EPSG:4326',
                                    X: Math.floor(point.x),
                                    Y: Math.floor(point.y)
                                };

                                const url = WMS_URL + L.Util.getParamString(params, WMS_URL);

                                fetch(url)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.features && data.features.length > 0) {
                                            let popupContent = '<table>';
                                            let highlightedFeatures = [];

                                            data.features.forEach(feature => {
                                                const layerName = feature.id.split('.')[0];
                                                const properties = feature.properties;
                                                
                                                switch (layerName) {
                                                    case '4326_caodo_chenhvenh':
                                                        popupContent += `<tr><td><strong>Chiều cao:</strong></td><td>${properties.text}</td></tr>`;
                                                        break;
                                                    case '4326_ho_chua_nuoc':
                                                        popupContent += `<tr><td><strong>Diện tích:</strong></td><td>${properties.dientich}</td></tr>`;
                                                        break;
                                                }
                                                highlightedFeatures.push(feature);
                                            });
                                            
                                            popupContent += '</table>';

                                            if (popupContent !== '<table></table>') {
                                                L.popup().setLatLng(e.latlng).setContent(popupContent).openOn(map);
                                                highlightLayer.clearLayers().addLayer(L.geoJSON(highlightedFeatures));
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Lỗi khi lấy thông tin đối tượng:', error));
                            });

                            // --- TẠO CHÚ THÍCH (LEGEND) TỰ ĐỘNG ---
                            const legendControl = L.control({ position: 'bottomright' });
                            legendControl.onAdd = function (map) {
                                const div = L.DomUtil.create('div', 'legend hidden');
                                div.innerHTML += '<h4>Chú thích</h4>';
                                wmsLayersConfig.forEach(config => {
                                    if(config.name.startsWith('4326_')) {
                                        const legendUrl = `${WMS_URL}?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=${WORKSPACE}:${config.name}`;
                                        div.innerHTML += `<div class="legend-item"><img src="${legendUrl}"> ${config.title}</div>`;
                                    }
                                });
                                return div;
                            };
                            legendControl.addTo(map);

                            const legendToggleControl = L.control({ position: 'bottomright' });
                            legendToggleControl.onAdd = function (map) {
                                const button = L.DomUtil.create('button', 'leaflet-bar leaflet-control');
                                button.innerHTML = 'Chú thích';
                                button.style.backgroundColor = 'white';
                                button.style.padding = '5px 10px';
                                button.onclick = function (e) {
                                    e.stopPropagation();
                                    document.querySelector('.legend').classList.toggle('hidden');
                                };
                                return button;
                            };
                            legendToggleControl.addTo(map);
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal để hiển thị 3D Viewer -->
<div class="modal fade" id="modal-3d-viewer" tabindex="-1" aria-labelledby="modal3DViewerLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal3DViewerLabel">Trình xem mô hình 3D</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <!-- Iframe sẽ tải trang mapglb.php -->
        <iframe src="https://nongdanviet.net/quanly/map/mapglb" title="Trình xem mô hình 3D"></iframe>
      </div>
    </div>
  </div>
</div>
