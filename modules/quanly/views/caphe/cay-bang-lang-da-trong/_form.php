<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\examples\models\ExampleModel;
use yii\widgets\MaskedInput;
use app\widgets\maps\LeafletMapAsset;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;

LeafletMapAsset::register($this);


/* @var $this yii\web\View */
/* @var $categories app\modules\quanly\models\DonViKinhTe */
/* @var $form yii\widgets\ActiveForm */

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$const['label'] = $controller->const['label'];

$this->title = Yii::t('app', $const['label'][$requestedAction->id] . ' ' . $controller->const['title']);
$this->params['breadcrumbs'][] = ['label' => $const['label']['index'] . ' ' . $controller->const['title'], 'url' => $controller->const['url']['index']];
$this->params['breadcrumbs'][] = $model->isNewRecord ? $const['label']['create'] . ' ' . $controller->const['title'] : $const['label']['update'] . ' ' . $controller->const['title'];

?>

<!-- <script src="<?= Yii::$app->homeUrl?>resources/core/js/jquery.inputmask.bundle.js" type="text/javascript"></script>
<script src="<?= Yii::$app->homeUrl?>resources/core/js/leaflet.ajax.js"></script> -->

<!-- CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<!-- JS -->
<script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>

<?php 
   
?>

<style>
.select2-container .select2-selection--multiple .select2-selection__rendered {
    display: flex;
    flex-direction: column;
}

.leaflet-control-locate a {
    cursor: pointer;
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
}
</style>



<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => ['encode' => false],
    ],
]) ?>

<div class="block block-themed">
    <div class="block-header">
        <h3 class="block-title">
            <?= ($model->isNewRecord) ? 'Thêm mới' : 'Cập nhật' ?>
        </h3>
    </div>

    <div class="block-content">

        

        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'long')->input('text', ['id' => 'geox-input']) ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'lat')->input('text', ['id' => 'geoy-input']) ?>
            </div>
        </div>


        <div class="row mt-3">
            <div class="col-lg-12">
                <div id="map" style="height: 600px"></div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'macay')->input('text') ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'thongtincay')->textArea(['rows' => 4]) ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 pb-3">
                <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary', 'id' => 'submitButton']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
var map = L.map('map').setView([<?= ($model->lat != null) ? $model->lat : 16.711630360842783  ?>,
    <?= ($model->long != null) ? $model->long : 106.63085460662843 ?>
], 18);

var icon = L.icon({
    iconUrl: 'https://auth.hcmgis.vn/uploads/icon/icons8-map-marker-96.png',
    //html: '<div style="background-color: blue; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white;"></div>',
    iconSize: [40, 40],
    iconAnchor: [20, 20],
    popupAnchor: [0, -48],
});
var marker = new L.marker([<?= ($model->lat != null) ? $model->lat : 16.711630360842783 ?>,
    <?= ($model->long != null) ? $model->long : 106.63085460662843 ?>
], {
    'draggable': 'true',
    'icon': icon,
});

var googleMap = L.tileLayer('http://{s}.google.com/vt/lyrs=' + 'r' + '&x={x}&y={y}&z={z}', {
    maxZoom: 24,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

var vetinh = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 24,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
});

var nen = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
    layers: 'total_feeling:orthor_4326_chenhvenh',
    format: 'image/png',
    transparent: true,
    //CQL_FILTER: 'status = 1',
    maxZoom: 22 // Đặt maxZoom là 22
}).addTo(map);

var overLayers = {
    'Nền bay chụp': nen,
};

var baseLayers = {
    "ggMap": googleMap,
    'Vệ tinh': vetinh,
};

L.control.layers(baseLayers, overLayers).addTo(map);
map.addLayer(googleMap, true);
var x = 10.7840441;
var y = 106.6939804;

L.control.locate({
    position: 'topleft',
    flyTo: true,
    keepCurrentZoomLevel: true,
    drawCircle: false,
    showPopup: false,
    strings: {
        title: "Định vị vị trí của bạn"
    },
    icon: 'fa fa-location-arrow',
    locateOptions: {
        enableHighAccuracy: true,
        maxZoom: 18,
        watch: false // <-- CHỐT TẮT THEO DÕI
    },
    clickBehavior: {
        inView: 'stop', 
        outOfView: 'setView', 
        inViewNotFollowing: 'setView'
     }
}).addTo(map);

setTimeout(() => {
    const btn = document.querySelector('.leaflet-control-locate a');
    if (btn) {
        btn.addEventListener('touchstart', function (e) {
            e.preventDefault();
            btn.click();
        });
    }
}, 1000);

 marker.on('dragend', function(event) {
    const position = event.target.getLatLng();
    map.panTo(position);
    $('#geoy-input').val(position.lat);
    $('#geox-input').val(position.lng);
});
map.addLayer(marker);

let lastLatLng = null;

map.on("locationfound", function(e) {
    const current = L.latLng(e.latitude, e.longitude);
    if (!lastLatLng || current.distanceTo(lastLatLng) > 5) {
        lastLatLng = current;
        $('#geoy-input').val(e.latitude);
        $('#geox-input').val(e.longitude);
        marker.setLatLng(current);
        map.setView(current, 18);
    }
});

// var map = L.map('map').setView([
//     <?= ($model->lat != null) ? $model->lat : 16.711630360842783 ?>,
//     <?= ($model->long != null) ? $model->long : 106.63085460662843 ?>
// ], 18);

// // Marker và icon
// var icon = L.icon({
//     iconUrl: 'https://auth.hcmgis.vn/uploads/icon/icons8-map-marker-96.png',
//     iconSize: [40, 40],
//     iconAnchor: [20, 20],
//     popupAnchor: [0, -48],
// });

// var marker = L.marker([
//     <?= ($model->lat != null) ? $model->lat : 16.711630360842783 ?>,
//     <?= ($model->long != null) ? $model->long : 106.63085460662843 ?>
// ], {
//     draggable: true,
//     icon: icon
// }).addTo(map);

// // Layers
// var googleMap = L.tileLayer('http://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
//     maxZoom: 24,
//     subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
// });

// var vetinh = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
//     maxZoom: 24,
//     subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
// });

// var nen = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
//     layers: 'total_feeling:orthor_4326_chenhvenh',
//     format: 'image/png',
//     transparent: true,
//     maxZoom: 22
// }).addTo(map);

// var baseLayers = {
//     "ggMap": googleMap,
//     "Vệ tinh": vetinh
// };
// var overLayers = {
//     "Nền bay chụp": nen
// };
// L.control.layers(baseLayers, overLayers).addTo(map);
// map.addLayer(googleMap);

// // Theo dõi vị trí liên tục
// let lastLatLng = null;
// let watchId = null;
// let locateButtonEl = null; // nút định vị

// // Tạo nút định vị
// const locateBtn = L.control({ position: 'topleft' });
// locateBtn.onAdd = function(map) {
//     const btn = L.DomUtil.create('button', 'leaflet-bar');
//     btn.type = 'button';
//     btn.innerHTML = '<i class="fa fa-location-arrow"></i>';
//     btn.title = 'Theo dõi vị trí';
//     btn.style.width = '34px';
//     btn.style.height = '34px';
//     btn.style.background = 'white';
//     btn.style.border = 'none';
//     btn.style.cursor = 'pointer';

//     btn.onclick = function(e) {
//         e.preventDefault();
//         startTracking();

//         // Ẩn nút sau khi bật định vị
//         btn.style.display = 'none';
//     };

//     locateButtonEl = btn; // lưu tham chiếu
//     return btn;
// };
// locateBtn.addTo(map);

// // Bắt đầu theo dõi GPS
// function startTracking() {
//     if (watchId !== null) return;

//     watchId = navigator.geolocation.watchPosition(
//         function(pos) {
//             const latlng = L.latLng(pos.coords.latitude, pos.coords.longitude);

//             if (!lastLatLng || latlng.distanceTo(lastLatLng) > 5) {
//                 lastLatLng = latlng;

//                 // cập nhật marker & input
//                 marker.setLatLng(latlng);
//                 map.setView(latlng, 18);
//                 $('#geoy-input').val(latlng.lat);
//                 $('#geox-input').val(latlng.lng);
//             }
//         },
//         function(err) {
//             console.error("Lỗi GPS:", err.message);
//             alert("Không lấy được vị trí. Vui lòng bật GPS hoặc cấp quyền.");
//         },
//         {
//             enableHighAccuracy: true,
//             maximumAge: 0,
//             timeout: 10000
//         }
//     );
// }

// // Khi kéo marker → dừng GPS + hiện lại nút
// marker.on('dragend', function(event) {
//     const position = event.target.getLatLng();
//     map.panTo(position);
//     $('#geoy-input').val(position.lat);
//     $('#geox-input').val(position.lng);

//     // Dừng GPS
//     if (watchId !== null) {
//         navigator.geolocation.clearWatch(watchId);
//         watchId = null;
//     }

//     // Hiện lại nút định vị
//     if (locateButtonEl) {
//         locateButtonEl.style.display = 'block';
//     }
// });

</script>