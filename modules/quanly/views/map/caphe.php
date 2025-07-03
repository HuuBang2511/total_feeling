<?php

use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\LeafletMap;
use app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset;
use app\widgets\maps\LeafletDrawAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;

LeafletMapAsset::register($this);
LeafletDrawAsset::register($this);
LeafletMeasureAsset::register($this);
LeafletLocateAsset::register($this);
?>

<!-- Tải plugin Leaflet-LocateControl -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
<script src="https://unpkg.com/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>

<div class="map-form">
    <div class="block block-themed">
        <div class="block-header">
            <h2 class="block-title"><?= 'Bản đồ đồi cà phê ' ?></h2>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-lg-12">
                    <div id="map" style="width: 100%; height: 600px; position:relative">></div>

                    <script>
                    var center = [16.71055, 106.63144];

                    // Create the map
                    var map = L.map('map', {
                        defaultExtentControl: true
                    }).setView(center, 18);

                    var baseMaps = {
                       

                        "Bản đồ Google": L.tileLayer('http://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
                            maxZoom: 22,
                            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                        }).addTo(map),

                        "Ảnh vệ tinh": L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                            maxZoom: 22,
                            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                        })
                    };
                    //Thêm lớp L.Control.Locate
                    var locateControl = new L.Control.Locate({
                        position: 'bottomleft',
                        strings: {
                            title: "Hiện vị trí",
                            popup: "Bạn đang ở đây"
                        },
                        drawCircle: true,
                        follow: true,
                    });
                    map.addControl(locateControl);

                    var measureControl = new L.Control.Measure({
                        position: 'bottomright',
                        primaryLengthUnit: 'meters',
                        secondaryLengthUnit: undefined,
                        primaryAreaUnit: 'sqmeters',
                        decPoint: ',',
                        thousandsSep: '.'
                    });
                    measureControl.addTo(map);
                            
                    // Thêm lớp locateControl vào bản đồ
                    //map.addControl(locateControl);

                    L.control.scale({
                        imperial: false,
                        maxWidth: 150
                    }).addTo(map);
                    var highlightLayer = L.featureGroup().addTo(map); // Lớp để highlight đối tượng được chọn

                    // Function to handle click events and display popup
                    function handleClickEvent(e, layer) {
                        getFeatureInfo(e.latlng, layer);
                    }

                    

                    var wmsNenbaychupLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:orthor_4326_chenhvenh',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    }).addTo(map);

                    var wmsNenDSMLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:dsm_4326_chenhvenh',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    });

                    var wmsHoChuaNuocLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_ho_chua_nuoc',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    });

                    var wmsRanhChenhvenhLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_ranh_chenhvenh',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    }).addTo(map);

                    var wmsDdmChenhvenhLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_ddm_c_chenhvenh',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    });

                    var wmsCaodoChenhvenhLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_caodo_chenhvenh',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    });

                    var wmsCocRanhDatLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_coc_ranhdat',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    });

                    var wmsCayNganHoaLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_cay_nganhoa',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    }).addTo(map);

                    var wmsCayGaoVangLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_cay_gaovang',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    }).addTo(map);

                    var wmsCayChuoiLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_cay_chuoi',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    });

                    var wmsCayCaPheLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_cay_caphe',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    }).addTo(map);

                    var wmsCaySenKhacLayer = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_cay_sen_khac',
                        format: 'image/png',
                        transparent: true,
                        //CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    });

                    

                    function getFeatureInfoUrl(layer, latlng, url) {


                        let size = map.getSize();
                        let bbox = map.getBounds().toBBoxString();
                        let point = map.latLngToContainerPoint(latlng, map.getZoom());

                        const FeatureInfoUrl = url +
                            `?SERVICE=WMS` +
                            `&VERSION=1.1.1` +
                            `&REQUEST=GetFeatureInfo` +
                            `&LAYERS=${layer}` +
                            `&QUERY_LAYERS=${layer}` +
                            `&STYLES=` +
                            `&BBOX=${bbox}` +
                            `&FEATURE_COUNT=5` +
                            `&HEIGHT=${size.y}` +
                            `&WIDTH=${size.x}` +
                            `&FORMAT=image/png` +
                            `&INFO_FORMAT=application/json` +
                            `&SRS=EPSG:4326` +
                            `&X=${Math.floor(point.x)}` +
                            `&Y=${Math.floor(point.y)}`;
                    

                        return FeatureInfoUrl;
                    }

                    // Add event listener for click events on each layer
                    map.on('click', function(e) {
                       


                        const layers = map._layers;

                        //console.log(layers);

                        for (const idx in layers) {
                            const layer = layers[idx];
                            //console.log(layer);
                            if (layer.wmsParams && layer._url && layer.wmsParams.layers != "") {
                                //console.log(layer._url);
                                let url = getFeatureInfoUrl(layer.wmsParams.layers, e.latlng, layer._url);

                                //console.log(url);

                                let layerName = layer.wmsParams.layers;
                                layerName = layerName.split(':');
                                layerName = String(layerName[1]);

                                //console.log(layerName);

                                fetch(url).
                                then(function(res) {
                                        return res.json()
                                    })
                                    .then(function(geojsonData) {
                                        //console.log(geojsonData.features);
                                        if (geojsonData.features && geojsonData.features.length > 0) {
                                            var popupContent = "" ;

                                            //onsole.log(geojsonData);

                                            var properties = geojsonData.features[0].properties;

                                            // if (layer.wmsParams.layers) {
                                            //     console.log("Lớp WMS đang bật:", layer.wmsParams.layers);
                                            // }

                                            if (layer.wmsParams.layers) {
                                                switch (layerName) {
                                                    case '4326_caodo_chenhvenh':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Chiều cao:</strong></td><td>" +
                                                            properties.text + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;

                                                    case '4326_ho_chua_nuoc':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Diện tích:</strong></td><td>" +
                                                            properties.dientich + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    
                                                }
                                            }

                                            //console.log(popupContent);
                                            if(popupContent != ''){
                                                var popup = L.popup()
                                                    .setLatLng(e.latlng)
                                                    .setContent(popupContent)
                                                    .openOn(map);
                                                highlightLayer.clearLayers(); // Xóa highlight trước đó (nếu có)
                                                var highlightedFeature = L.geoJSON(geojsonData.features[0]);
                                                console.log(highlightedFeature);
                                                highlightLayer.addLayer(highlightedFeature);
                                            }   
                                            
                                        }
                                    })
                            }

                        }

                    });

                    // Add control layers to the map
                    var overlayMaps = {
                        // "Bản đồ nền": wmsBase,
                        "Ảnh nền": wmsNenbaychupLayer,
                        "Ảnh nền DSM": wmsNenDSMLayer,
                        "Hồ chứa nước": wmsHoChuaNuocLayer,
                        "Ranh chênh vênh" : wmsRanhChenhvenhLayer,
                        "Đường đồng mức": wmsDdmChenhvenhLayer,
                        "Cao độ chênh vênh": wmsCaodoChenhvenhLayer,
                        "Cọc ranh đất": wmsCocRanhDatLayer,
                        "Cây ngân hoa": wmsCayNganHoaLayer,
                        "Cây gáo vàng": wmsCayGaoVangLayer,
                        "Cây chuối": wmsCayChuoiLayer,
                        "Cây cà phê": wmsCayCaPheLayer,
                        "Cây sưa": wmsCaySenKhacLayer,
                        "Highlight": highlightLayer // Thêm lớp highlight vào control layers

                    };
                    // Tạo legend control
                    var legendControl = L.control({
                        position: 'bottomright'
                    });

                    legendControl.onAdd = function(map) {
                        var div = L.DomUtil.create('div', 'legend');
                        div.innerHTML += '<h4>Legend</h4>';
                        div.innerHTML +=
                            '<img src="https://nongdanviet.net/geoserver/total_feeling/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=total_feeling:4326_cay_caphe"> Cây cà phê<br>';
                        div.innerHTML +=
                            '<img src="https://nongdanviet.net/geoserver/total_feeling/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=total_feeling:4326_cay_chuoi"> Cây chuối<br>';
                        div.innerHTML +=
                            '<img src="https://nongdanviet.net/geoserver/total_feeling/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=total_feeling:4326_cay_gaovang"> Cây gáo vàng<br>';
                        div.innerHTML +=
                            '<img src="https://nongdanviet.net/geoserver/total_feeling/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=total_feeling:4326_cay_nganhoa"> Cây ngân hoa<br>';
                        div.innerHTML +=
                            '<img src="https://nongdanviet.net/geoserver/total_feeling/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=total_feeling:4326_cay_sen_khac"> Cây sưa<br>';
                        div.innerHTML +=
                            '<img src="https://nongdanviet.net/geoserver/total_feeling/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=total_feeling:4326_coc_ranhdat"> Cọc ranh đất<br>';
                        div.innerHTML +=
                            '<img src="https://nongdanviet.net/geoserver/total_feeling/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=total_feeling:4326_ddm_c_chenhvenh"> Đường đồng mức<br>';
                        div.innerHTML +=
                            '<img src="https://nongdanviet.net/geoserver/total_feeling/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=total_feeling:4326_ranh_chenhvenh"> Ranh chênh vênh<br>';
                        div.innerHTML +=
                            '<img src="https://nongdanviet.net/geoserver/total_feeling/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=total_feeling:4326_caodo_chenhvenh"> Cao độ chênh vênh<br>';
                        return div;
                    };

                    legendControl.addTo(map);

                    // Tạo một nút bật/tắt legend riêng lẻ
                    var legendToggleControl = L.control({
                        position: 'bottomright'
                    });

                    legendToggleControl.onAdd = function(map) {
                        var div = L.DomUtil.create('div', 'legend-toggle');
                        div.innerHTML = '<button id="legend-toggle-btn"> Chú thích</button>';
                        return div;
                    };

                    legendToggleControl.addTo(map);

                    // Thêm sự kiện click cho nút bật/tắt legend riêng lẻ
                    document.getElementById('legend-toggle-btn').addEventListener('click', function() {
                        var legendDiv = document.querySelector('.legend');
                        if (legendDiv.style.display === 'none' || legendDiv.style.display === '') {
                            legendDiv.style.display = 'block'; // Hiển thị legend nếu đang ẩn
                        } else {
                            legendDiv.style.display = 'none'; // Ẩn legend nếu đang hiển thị
                        }
                    });

                    var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);
                    
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS for popup table */
.popup-content {
    max-width: 100%;
    /* Đảm bảo popup không vượt quá chiều rộng của màn hình */
    overflow-x: auto;
    /* Cho phép người dùng cuộn ngang nếu nội dung quá rộng */
}

/* CSS for table */
.popup-table {
    width: 100%;
    border-collapse: collapse;
}

/* CSS for table header */
.popup-table th {
    background-color: #f2f2f2;
    /* Màu nền cho tiêu đề cột */
    padding: 8px;
    text-align: left;
}

/* CSS for table data */
.popup-table td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
    /* Đường viền dưới của mỗi hàng */
}

/* CSS for alternate row color */
.popup-table tr:nth-child(even) {
    background-color: #f2f2f2;
    /* Màu nền cho hàng chẵn */
}

/* CSS for table header on hover */
.popup-table th:hover {
    background-color: #ddd;
    /* Màu nền khi di chuột qua tiêu đề cột */
}

/* Responsive design */
@media screen and (max-width: 600px) {

    /* Thiết lập độ rộng của popup cho các thiết bị có màn hình nhỏ */
    .popup-content {
        width: 100%;
    }

    /* Thiết lập độ rộng của bảng để nó không bị tràn khỏi màn hình */
    .popup-table {
        overflow-x: auto;
    }
}

.legend {
    background-color: white;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    display: none;
}

.legend img {
    width: 20px;
    height: auto;
    margin-right: 5px;
}
</style>