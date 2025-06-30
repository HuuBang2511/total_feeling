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
            <h2 class="block-title"><?= 'Water Network ' ?></h2>
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
                    }).setView(center, 16);

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

                    

                    var wmsNenbaychupLayer = L.tileLayer.wms('http://103.9.77.141:8080/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:orthor_4326_chenhvenh',
                        format: 'image/png',
                        transparent: true,
                        CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    });

                    var wmsNenDSMLayer = L.tileLayer.wms('http://103.9.77.141:8080/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:dsm_4326_chenhvenh',
                        format: 'image/png',
                        transparent: true,
                        CQL_FILTER: 'status = 1',
                        maxZoom: 22 // Đặt maxZoom là 22
                    });

                    var wmsCayCaPheLayer = L.tileLayer.wms('http://103.9.77.141:8080/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:4326_cay_caphe',
                        format: 'image/png',
                        transparent: true,
                        CQL_FILTER: 'status = 1',
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
                                            //var popupContent = "popup" ;

                                            //onsole.log(geojsonData);

                                            var properties = geojsonData.features[0].properties;

                                            // if (layer.wmsParams.layers) {
                                            //     console.log("Lớp WMS đang bật:", layer.wmsParams.layers);
                                            // }

                                            if (layer.wmsParams.layers) {
                                                switch (layerName) {
                                                    case 'gd_data_logger':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Chức năng:</strong></td><td>" +
                                                            properties.chucnang + "</td></tr>" +
                                                            "<tr><td><strong>Vị trí:</strong></td><td>" +
                                                            properties.vitri + "</td></tr>" +
                                                            "<tr><td><strong>Tình trạng:</strong></td><td>" +
                                                            properties.tinhtrang + "</td></tr>" +
                                                            "<tr><td><strong>Ghi chú:</strong></td><td>" +
                                                            properties.ghichu + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    case 'gd_dongho_kh_gd':
                                                        var feature = geojsonData.features[0];
                                                        var properties = feature.properties;

                                                        var featureId = '';
                                                        if (feature.id && feature.id.includes('.')) {
                                                            featureId = feature.id.split('.')[1];
                                                            console.log('feature.id:', feature.id);
                                                        } else {
                                                            console.warn(
                                                                'ID không hợp lệ hoặc không tồn tại:',
                                                                feature.id);
                                                        }

                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Danh bạ:</strong></td><td>" +
                                                            properties.dbdonghonu + "</td></tr>" +
                                                            "<tr><td><strong>Số thân đồng:</strong></td><td>" +
                                                            properties.sothandong + "</td></tr>" +
                                                            "<tr><td><strong>Tên KH:</strong></td><td>" +
                                                            properties.tenkhachha + "</td></tr>" +
                                                            "<tr><td><strong>ĐTDD:</strong></td><td>" +
                                                            properties.dtdd + "</td></tr>" +
                                                            "<tr><td><strong>Địa chỉ:</strong></td><td>" +
                                                            properties.diachi + "</td></tr>" +
                                                            "<tr><td><strong>Hiệu:</strong></td><td>" +
                                                            properties.hieudongho + "</td></tr>" +
                                                            "<tr><td><strong>Vị trí lắp đặt:</strong></td><td>" +
                                                            properties.vitrilapda + "</td></tr>" +
                                                            "<tr><td><strong>Tình trạng:</strong></td><td>" +
                                                            properties.tinhtrang + "</td></tr>" +
                                                            "<tr><td><strong>Bản Vẽ:</strong></td><td><p><a href=\"https://gisapi.giadinhwater.vn/gdw/banvehoancong/14091476272\" target=\"_blank\">Hoàn Công</a></p></td></tr>" +
                                                            "<tr><td><strong>Xem chi tiết</strong></td><td><p><a href=\"http://hpngis.online/quanly/capnuocgd/gd-dongho-kh-gd/view?id=" +
                                                            featureId +
                                                            "\" target=\"_blank\">Thông tin chi tiết</a></p></td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;

                                                    case 'gd_dongho_tong_gd':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Hiệu:</strong></td><td>" +
                                                            properties.hieudongho + "</td></tr>" +
                                                            "<tr><td><strong>Ngày lắp đặt:</strong></td><td>" +
                                                            properties.ngaylapdat + "</td></tr>" +
                                                            "<tr><td><strong>Vị trí:</strong></td><td>" +
                                                            properties.vitrilapda + "</td></tr>" +
                                                            "<tr><td><strong>Đơn vị thi công:</strong></td><td>" +
                                                            properties.donvithico + "</td></tr>" +
                                                            "<tr><td><strong>Cỡ ĐH:</strong></td><td>" +
                                                            properties.codongho + "</td></tr>" +
                                                            "<tr><td><strong>Tình trạng:</strong></td><td>" +
                                                            properties.tinhtrang + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    case 'gd_hamkythuat':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Tên hầm:</strong></td><td>" +
                                                            properties.tenhamkyth + "</td></tr>" +
                                                            "<tr><td><strong>Kích thước:</strong></td><td>" +
                                                            properties.kichthuoch + "</td></tr>" +
                                                            "<tr><td><strong>Tình trạng:</strong></td><td>" +
                                                            properties.tinhtrangh + "</td></tr>" +
                                                            "<tr><td><strong>Ghi chú:</strong></td><td>" +
                                                            properties.ghichu + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    case 'gd_ongcai':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Cỡ ống:</strong></td><td>" +
                                                            properties.coong + "</td></tr>" +
                                                            "<tr><td><strong>Vật liệu:</strong></td><td>" +
                                                            properties.vatlieu + "</td></tr>" +
                                                            "<tr><td><strong>Tên công trình:</strong></td><td>" +
                                                            properties.tencongtri + "</td></tr>" +
                                                            "<tr><td><strong>Đơn vị thi công:</strong></td><td>" +
                                                            properties.donvithico + "</td></tr>" +
                                                            "<tr><td><strong>Tình trạng:</strong></td><td>" +
                                                            properties.tinhtrang + "</td></tr>" +
                                                            "<tr><td><strong>Ghi chú:</strong></td><td>" +
                                                            properties.ghichu + "</td></tr>" +
                                                            "<tr><td><strong>Năm lắp đặt:</strong></td><td>" +
                                                            properties.namlapdat + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    case 'gd_ongnganh':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>ID ống:</strong></td><td>" +
                                                            properties.idduongong + "</td></tr>" +
                                                            "<tr><td><strong>Vật liệu:</strong></td><td>" +
                                                            properties.vatlieu + "</td></tr>" +
                                                            "<tr><td><strong>Tình trạng:</strong></td><td>" +
                                                            properties.tinhtrang + "</td></tr>" +
                                                            "<tr><td><strong>Năm lắp đặt:</strong></td><td>" +
                                                            properties.namlapdat + "</td></tr>" +
                                                            "<tr><td><strong>Cống:</strong></td><td>" +
                                                            properties.coong + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    case 'v2_4326_ONGTRUYENDAN':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Vật liệu:</strong></td><td>" +
                                                            properties.vatlieu + "</td></tr>" +
                                                            "<tr><td><strong>Cỡ ống:</strong></td><td>" +
                                                            properties.coong + "</td></tr>" +
                                                            "<tr><td><strong>Tên công trình:</strong></td><td>" +
                                                            properties.tencongtri + "</td></tr>" +
                                                            "<tr><td><strong>Năm lắp đặt:</strong></td><td>" +
                                                            properties.namlapdat + "</td></tr>" +
                                                            "<tr><td><strong>Đơn vị thiết kế:</strong></td><td>" +
                                                            properties.donvithiet + "</td></tr>" +
                                                            "<tr><td><strong>Đơn vị thi công:</strong></td><td>" +
                                                            properties.donvithico + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    case 'gd_vanphanphoi':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>ID hầm:</strong></td><td>" +
                                                            properties.idhamkythu + "</td></tr>" +
                                                            "<tr><td><strong>cochiakhoa:</strong></td><td>" +
                                                            properties.cochiakhoa + "</td></tr>" +
                                                            "<tr><td><strong>Vật liệu:</strong></td><td>" +
                                                            properties.vatlieu + "</td></tr>" +
                                                            "<tr><td><strong>Mã DMA:</strong></td><td>" +
                                                            properties.madma + "</td></tr>" +
                                                            "<tr><td><strong>Vị trí:</strong></td><td>" +
                                                            properties.vitrivan + "</td></tr>" +
                                                            "<tr><td><strong>Tình trạng:</strong></td><td>" +
                                                            properties.tinhtrang + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    case 'gd_trambom':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Tên:</strong></td><td>" +
                                                            properties.tentram + "</td></tr>" +
                                                            "<tr><td><strong>Số lượng bom:</strong></td><td>" +
                                                            properties.soluongbom + "</td></tr>" +
                                                            "<tr><td><strong>Đơn vị quản lý:</strong></td><td>" +
                                                            properties.donviquanl + "</td></tr>" +
                                                            "<tr><td><strong>Ghi chú:</strong></td><td>" +
                                                            properties.ghichu + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    case 'gd_tramcuuhoa':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>ID trạm:</strong></td><td>" +
                                                            properties.idtruhong + "</td></tr>" +
                                                            "<tr><td><strong>Kích cỡ:</strong></td><td>" +
                                                            properties.kichco + "</td></tr>" +
                                                            "<tr><td><strong>Kích thước:</strong></td><td>" +
                                                            properties.kcmiengphu + "</td></tr>" +
                                                            "<tr><td><strong>Loại trụ:</strong></td><td>" +
                                                            properties.loaitruhon + "</td></tr>" +
                                                            "<tr><td><strong>Hiệu:</strong></td><td>" +
                                                            properties.hieu + "</td></tr>" +
                                                            "<tr><td><strong>Tiêu chuẩn:</strong></td><td>" +
                                                            properties.tieuchuan + "</td></tr>" +
                                                            "<tr><td><strong>Mã DMA:</strong></td><td>" +
                                                            properties.madma + "</td></tr>" +
                                                            "<tr><td><strong>Vật liệu:</strong></td><td>" +
                                                            properties.vatlieu + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
        
                                                    case 'v2_gd_suco':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Mã sự cố:</strong></td><td>" +
                                                            properties.masuco + "</td></tr>" +
                                                            "<tr><td><strong>Số nhà:</strong></td><td>" +
                                                            properties.sonha + "</td></tr>" +
                                                            "<tr><td><strong>Đường:</strong></td><td>" +
                                                            properties.duong + "</td></tr>" +
                                                            "<tr><td><strong>Ngày phát hiện:</strong></td><td>" +
                                                            properties.ngayphathien + "</td></tr>" +
                                                            "<tr><td><strong>Người phát hiện:</strong></td><td>" +
                                                            properties.nguoiphathien + "</td></tr>" +
                                                            "<tr><td><strong>Ngày sửa chữa:</strong></td><td>" +
                                                            properties.ngaysuachua + "</td></tr>" +
                                                            "<tr><td><strong>Đơn vị:</strong></td><td>" +
                                                            properties.donvisuachua + "</td></tr>" +
                                                            "<tr><td><strong>Vị trí phát hiện:</strong></td><td>" +
                                                            properties.vitriphathien + "</td></tr>" +
                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                    case 'v2_4326_DMA':
                                                        var popupContent = "<div class='popup-content'>" +
                                                            "<table>" +
                                                            "<tr><td><strong>Mã DMA:</strong></td><td>" +
                                                            properties.madma + "</td></tr>" +
                                                            "<tr><td><strong>Số van:</strong></td><td>" +
                                                            properties.sovan + "</td></tr>" +
                                                            "<tr><td><strong>Số trụ:</strong></td><td>" +
                                                            properties.sotru + "</td></tr>" +
                                                            "<tr><td><strong>Số đầu nối:</strong></td><td>" +
                                                            properties.sodaunoi + "</td></tr>" +

                                                            "</table>" +
                                                            "</div>";
                                                        break;
                                                }
                                            }

                                            var popup = L.popup()
                                                .setLatLng(e.latlng)
                                                .setContent(popupContent)
                                                .openOn(map);
                                            highlightLayer.clearLayers(); // Xóa highlight trước đó (nếu có)
                                            var highlightedFeature = L.geoJSON(geojsonData.features[0]);
                                            console.log(highlightedFeature);
                                            highlightLayer.addLayer(highlightedFeature);

                                        }
                                    })
                            }

                        }

                    });

                    // Add control layers to the map
                    var overlayMaps = {
                        // "Bản đồ nền": wmsBase,
                        "Ảnh bay chụp": wmsNenbaychupLayer,
                        "Ảnh DSM": wmsNenDSMLayer,
                        // "Đồng hồ khách hàng": wmsDonghoKhLayer,
                        // "Đồng hồ tổng": wmsDonghoTongLayer,
                        // "Hầm kỹ thuật": wmsHamLayer,
                        // "Ống cái Đang sử dụng": wmsOngCaiLayer,
                        // "Ống cái Đã Hủy": wmsOngCaiDHLayer,
                        // "Ống ngánh": wmsOngNganhLayer,
                        // "Ống truyền dẫn": wmsOngTruyenDanLayer,
                        // "Trạm bơm": wmsTrambomLayer,
                        // "Trụ cứu hỏa": wmsTramCuuHoaLayer,
                        // "Van phân phối": wmsVanPhanPhoiLayer,
                        // "Sự cố điểm bể": wmsSucoLayer,
                        // "DMA": wmsDMA,
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
                            '<img src="http://103.9.77.141:8080/geoserver/giadinh/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giadinh:gd_dongho_kh_gd"> Đồng hồ KH<br>';
                        div.innerHTML +=
                            '<img src="http://103.9.77.141:8080/geoserver/giadinh/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giadinh:gd_dongho_tong_gd"> Đồng hồ tổng<br>';
                        div.innerHTML +=
                            '<img src="http://103.9.77.141:8080/geoserver/giadinh/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giadinh:gd_trambom"> Trạm bơm<br>';
                        div.innerHTML +=
                            '<img src="http://103.9.77.141:8080/geoserver/giadinh/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giadinh:gd_tramcuuhoa"> Trạm cứu hỏa<br>';
                        div.innerHTML +=
                            '<img src="http://103.9.77.141:8080/geoserver/giadinh/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giadinh:gd_vanphanphoi"> Van phân phối<br>';
                        div.innerHTML +=
                            '<img src="http://103.9.77.141:8080/geoserver/giadinh/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giadinh:gd_hamkythuat"> Hầm kỹ thuật<br>';
                        div.innerHTML +=
                            '<img src="http://103.9.77.141:8080/geoserver/giadinh/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giadinh:gd_ongcai"> Ống cái<br>';
                        div.innerHTML +=
                            '<img src="http://103.9.77.141:8080/geoserver/giadinh/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giadinh:gd_ongnganh"> Ống ngánh<br>';
                        div.innerHTML +=
                            '<img src="http://103.9.77.141:8080/geoserver/giadinh/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=giadinh:gd_suco"> Sự cố<br>';
                        return div;
                    };

                    //legendControl.addTo(map);

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