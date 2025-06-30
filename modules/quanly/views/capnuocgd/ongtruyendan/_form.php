<?php

use app\modules\APPConfig;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use app\widgets\maps\types\LatLng;
use app\widgets\maps\layers\DraggableMarker;
use app\widgets\maps\LeafletMap;
use app\widgets\maps\layers\TileLayer;
use \app\widgets\maps\controls\Layers;
use app\widgets\maps\LeafletMapAsset;
use yii\helpers\Url;
use app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset;
use app\widgets\maps\LeafletDrawAsset;

LeafletMapAsset::register($this);
LeafletDrawAsset::register($this);
LeafletMeasureAsset::register($this);

LeafletMapAsset::register($this);


$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['search'] . ' ' . $controller->title, 'url' => $controller->url];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gd-data-logger-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="block block-themed">

        <div class="block-header">
            <h2 class="block-title"><?= $this->title ?></h2>
        </div>
        <div class="block-content">
        <div class="row pb-2">
                <div class="col-lg-12">
                    <div id="map" style="height: 500px"></div>
                    <?= Html::hiddenInput('Ongtruyendan[geojson]', $model->geojson, ['id' => 'geojson']) ?>

                    <?php $center_view = Yii::$app->params['center'] ?>
                    <script>
                        // center of the map
                        var center = [10.804291919691535, 106.69527258767485];

                        // Create the map
                        var map = L.map('map').setView(center, 14);

                        var baseMaps = {
                                "Bản đồ Google": L.tileLayer('http://{s}.google.com/vt/lyrs=' + 'r' + '&x={x}&y={y}&z={z}', {
                                    maxZoom: 24,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                }).addTo(map),
                                "Ảnh vệ tinh": L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                                    maxZoom: 24,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                                }),
                                // "MapBox": L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2thZGFtYmkiLCJhIjoiY2lqdndsZGg3MGNua3U1bTVmcnRqM2xvbiJ9.9I5ggqzhUVrErEQ328syYQ#3/0.00/0.00', {
                                //     maxZoom: 18,
                                //     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                                //     id: 'streets-v9',
                                // }),
                                // "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                //     attribution: '© <a href="https://www.openstreetmap.org" target="_blank">OpenStreetMap</a>',
                                //     maxZoom: 18
                                // }),
                            };
// Thêm lớp L.Control.Locate
                        // var locateControl = new L.Control.Locate({
                        //     position: 'bottomleft',
                        //     strings: {
                        //         title: "Hiện vị trí",
                        //         popup: "Bạn đang ở đây"
                        //     },
                        //     drawCircle: true,
                        //     follow: true,
                        // });

                        // // Thêm lớp locateControl vào bản đồ
                        // map.addControl(locateControl);

                        var layerControl = L.control.layers(baseMaps);
                        layerControl.addTo(map);
                        // add a marker in the given location
                        //L.marker(center).addTo(map);


                        // Initialise the FeatureGroup to store editable layers
                        var editableLayers = new L.FeatureGroup();
                        map.addLayer(editableLayers);

                        var drawPluginOptions = {
                            position: 'topleft',
                            draw: {
                                polygon: false,
                                // disable toolbar item by setting it to false
                                polyline: {
                                    shapeOptions: {
                                        color: '#f357a1',
                                        weight: 10
                                    }
                                },
                                polyline: true,
                                line: true,
                                circle: false, // Turns off this drawing tool
                                circlemarker: false, // Turns off this drawing tool
                                rectangle: false,
                                marker: false,
                            },
                            edit: {
                                featureGroup: editableLayers, //REQUIRED!!
                                remove: true,
                                edit: true,
                            }
                        };

                        // Initialise the draw control and pass it the FeatureGroup of editable layers
                        var drawControl = new L.Control.Draw(drawPluginOptions);
                        map.addControl(drawControl);

                        <?php if($model->geojson != null) :?>

                        var states = [{
                            "type": "Feature",
                            "properties": {"": ""},
                            "geometry":  <?= $model->geojson ?>
                        }];

                        L.geoJSON(states, {
                            onEachFeature: function (feature, layer) {
                                if (layer instanceof L.Polygon) {
                                    L.polygon(layer.getLatLngs()).addTo(editableLayers);
                                }
                                if (layer instanceof L.Marker) {
                                    L.marker(layer.getLatLng()).addTo(editableLayers);
                                }
                                if (layer instanceof L.Polyline) {
                                    L.polyline(layer.getLatLngs()).addTo(editableLayers);
                                }

                            }
                        });

                        // Get bounds object
                        var bounds = editableLayers.getBounds()

                        //Fit the map to the polygon bounds
                        map.fitBounds(bounds)

                        // Or center on the polygon
                        var centerstates = bounds.getCenter()
                        map.panTo(centerstates)
                        <?php endif;?>


                        //var editableLayers = new L.FeatureGroup();
                        map.addLayer(editableLayers);
                        map.on('draw:created', function (e) {
                            var type = e.layerType,
                                layer = e.layer;
                            $('#geojson').val(JSON.stringify(layer.toGeoJSON().geometry));

                            editableLayers.addLayer(layer);
                        });

                        map.on('draw:edited', function (e) {
                            var layers = e.layers;
                            layers.eachLayer(function (layer) {
                                $('#geojson').val(JSON.stringify(layer.toGeoJSON().geometry));
                            });
                        });
                        var wmsDonghoKhLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_dongho_kh_gd',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            minZoom: 18,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsHamLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_hamkythuat',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsOngCaiDHLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_ongcai',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: "status = 1", // Thêm điều kiện lọc tinhtrang là 'DH'
                            minZoom: 18,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsOngNganhLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_ongnganh',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            minZoom: 18, // Đặt maxZoom là 22
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsTramCuuHoaLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_tramcuuhoa',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            minZoom: 20,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsVanPhanPhoiLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_vanphanphoi',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            minZoom: 20,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                    </script>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'vatlieu')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'coong')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'namlapdat')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'tencongtri')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'donvithiet')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'donvithico')->textInput() ?>
                </div>
            </div>
            

            <div class="row">
                <div class="form-group col-lg-12">
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary float-left']) ?>
                    <?= Html::button('Quay lại', ['class' => 'btn btn-light float-right', 'type' => 'button', 'onclick' => "history.back()"]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
