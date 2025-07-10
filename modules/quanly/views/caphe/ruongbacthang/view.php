<?php

use yii\helpers\Url;
use yii\widgets\DetailView;
use app\widgets\maps\LeafletMapAsset;
use yii\helpers\Html;
use app\widgets\gridview\GridView;

LeafletMapAsset::register($this);

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['search'] . ' ' . $controller->title, 'url' => $controller->url];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dma-view">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title"><?= $this->title ?></h3>
                    <div class="block-options">
                        <a class="btn btn-warning btn-sm" href="<?= Url::to(['update', 'id' => $model->id]) ?>">Cập nhật</a>
                        <a class="btn btn-light btn-sm" href="<?= Url::to(['index']) ?>">Danh sách</a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-lg-12 pb-2">

                            <div id="map" style="height: 400px"></div>
                            <script>

                                // center of the map
                                var center = [16.711630360842783, 106.63085460662843];

                                // Create the map
                                var map = L.map('map').setView(center, 14);
                                var baseMaps = {
                                    "Bản đồ Google": L.tileLayer('http://{s}.google.com/vt/lyrs=' + 'r' + '&x={x}&y={y}&z={z}', {
                                        maxZoom: 22,
                                        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                    }).addTo(map),
                                    "Ảnh vệ tinh": L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                                        maxZoom: 22,
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
                                <?php if($geojson != null) :?>
                                var states = [{
                                    "type": "Feature",
                                    "properties": {"": ""},
                                    "geometry": <?= $geojson ?>
                                }];

                                var polygon = L.geoJSON(states).addTo(map);

                                var bounds = polygon.getBounds()
                                map.fitBounds(bounds)

                                var centerpolygon = bounds.getCenter()
                                map.panTo(centerpolygon)
                                <?php endif;?>
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    //'id',
                                    //'geom',
                                    //'objectid',
                                    'maso',
                                    'ngay',
                                    'ten',
                                    'dacdiem',
                                    'ghichu',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   

</div>
