<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\crud\CrudAsset;
use app\modules\services\UtilityService;
use kartik\detail\DetailView;
use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;
LeafletLocateAsset::register($this);

LeafletMapAsset::register($this);
CrudAsset::register($this);

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = implode(' ', [$label[$requestedAction->id],$controller->title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $label['index'] . ' ' . $controller->title), 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>




<style>
#map {
    width: 100%;
    height: 90vh;
    border: 1px solid #0665d0
}

.leaflet-top.leaflet-right {
    max-height: 500px;
    overflow-y: scroll;
}
</style>

<script src="<?= Yii::$app->homeUrl?>resources/core/js/leaflet.ajax.js"></script>


<div class="row">
    <div class="col-lg-12">
        <div class="block block-themed">
            <div class="block-header">
                <h3 class="block-title">Thông tin cây cà phê</h3>
                <div class="block-options">
                    <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                </div>
            </div>

            <div class="block-content tab-content">
                <div class="tab-pane active" id="thongtinnocgia-view">
                    <div class="row">
                        
                        <div class="col-lg-12">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="block-content">
                <div class="row px-3">
                    <div class="col-lg-12 form-group">
                        <a href="javascript:history.back()" class="btn btn-light float-end"><i
                                class="fa fa-arrow-left"></i>
                            Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "size" => Modal::SIZE_EXTRA_LARGE,
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<script type="module">
   
    var map = L.map('map').setView([<?= ($model->lat != null) ? $model->lat : '16.71055' ?>,
        <?= ($model->long != null) ? $model->long : '106.63144' ?>
    ], 20);


    var layerGMapSatellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    var layerGmapStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });


    var baseLayers = {
        "GGMap": layerGmapStreets,
        "Vệ tinh": layerGMapSatellite,
    };

    var caycaphe =  L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
        layers: 'total_feeling:4326_cay_caphe',
        format: 'image/png',
        transparent: true,
        maxZoom: 22 // Đặt maxZoom là 22
    });

    var nen = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
        layers: 'total_feeling:orthor_4326_chenhvenh',
        format: 'image/png',
        transparent: true,
        //CQL_FILTER: 'status = 1',
        maxZoom: 22 // Đặt maxZoom là 22
    });

    var overLayers = {
        'Nền bay chụp': nen,
        'Cây cà phê': caycaphe,
    };

    L.control.layers(baseLayers, overLayers).addTo(map);
    map.addLayer(layerGmapStreets, true);

    var icon = L.icon({
        iconUrl: 'https://auth.hcmgis.vn/uploads/icon/icons8-map-marker-96.png',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -48],
    });

    <?php if ($model->long != null && $model->lat != null) : ?>
    var marker = L.marker([<?= $model->lat ?>, <?= $model->long ?>], {
        'icon': icon,
    }).addTo(map);
    <?php endif; ?>

    
    
</script>