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
use yii\helpers\Url;

use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset;
use app\widgets\maps\LeafletDrawAsset;

LeafletMapAsset::register($this);
LeafletDrawAsset::register($this);
LeafletMeasureAsset::register($this);


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
                    <?= Html::hiddenInput('Ruongbacthang[geojson]', $model->geojson, ['id' => 'geojson']) ?>

                    <?php $center_view = Yii::$app->params['center'] ?>
                    <script>
                    var center = [16.711630360842783, 106.63085460662843];

                    // Create the map
                    var map = L.map('map').setView(center, 18);

                    var baseMaps = {
                        "Bản đồ Google": L.tileLayer('http://{s}.google.com/vt/lyrs=' + 'r' +
                        '&x={x}&y={y}&z={z}', {
                            maxZoom: 22,
                            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                        }).addTo(map),
                        "Ảnh vệ tinh": L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                            maxZoom: 22,
                            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                        }),
                    };

                    var nen = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
                        layers: 'total_feeling:orthor_4326_chenhvenh',
                        format: 'image/png',
                        transparent: true,
                        maxZoom: 22
                    }).addTo(map);

                    var overLayers = {
                        'Nền bay chụp': nen,
                    };


                    var layerControl = L.control.layers(baseMaps, overLayers);
                    layerControl.addTo(map);
                    // add a marker in the given location
                    //L.marker(center).addTo(map);


                    // Initialise the FeatureGroup to store editable layers
                    var editableLayers = new L.FeatureGroup();
                    map.addLayer(editableLayers);

                    var drawPluginOptions = {
                        position: 'topleft',
                        draw: {
                            polygon: true,
                            // disable toolbar item by setting it to false
                            polyline: {
                                shapeOptions: {
                                    color: '#f357a1',
                                    weight: 10
                                }
                            },
                            polyline: false,
                            line: false,
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
                        "properties": {
                            "": ""
                        },
                        "geometry": <?= $model->geojson ?>
                    }];

                    L.geoJSON(states, {
                        onEachFeature: function(feature, layer) {
                            if (layer instanceof L.Polygon) {
                                L.polygon(layer.getLatLngs()).addTo(editableLayers);
                            }
                            // if (layer instanceof L.Marker) {
                            //     L.marker(layer.getLatLng()).addTo(editableLayers);
                            // }
                            // if (layer instanceof L.Polyline) {
                            //     L.polyline(layer.getLatLngs()).addTo(editableLayers);
                            // }

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
                    map.on('draw:created', function(e) {
                        var type = e.layerType,
                            layer = e.layer;
                        $('#geojson').val(JSON.stringify(layer.toGeoJSON().geometry));

                        editableLayers.addLayer(layer);
                    });

                    map.on('draw:edited', function(e) {
                        var layers = e.layers;
                        layers.eachLayer(function(layer) {
                            $('#geojson').val(JSON.stringify(layer.toGeoJSON().geometry));
                        });
                    });
                    </script>
                </div>
            </div>
            
             <div class="row mt-3">
                <div class="col-lg-4">
                    <?= $form->field($model, 'maso')->input('text') ?>
                </div>
                
                <div class="col-lg-4">
                    <?=
                        $form->field($model, 'ngay')->widget(DatePicker::classname(), [
                            'options' => [
                            'placeholder' => 'Ngày ...',
                                    
                        ],
                            'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd/mm/yyyy'
                        ]
                        ]);
                    ?>
                </div>

                <div class="col-lg-4">
                    <?= $form->field($model, 'ten')->input('text') ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <?= $form->field($model, 'dacdiem')->textArea(['rows' => 4]) ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <?= $form->field($model, 'ghichu')->textArea(['rows' => 4]) ?>
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