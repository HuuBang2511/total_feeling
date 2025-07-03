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
            <div class="col-lg-6">
                <?= $form->field($model, 'macay')->input('text') ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'loaicaphe_id')->widget(Select2::className(), [
                        'data' => ArrayHelper::map($loaicaphe, 'id', 'ten'),
                        'options' => ['prompt' => 'Chọn loại cà phê'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                ]) ?>
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
var map = L.map('map').setView([
    <?= ($model->lat != null) ? $model->lat : 16.711630360842783 ?>,
    <?= ($model->long != null) ? $model->long : 106.63085460662843 ?>
], 18);

// Lớp nền
var googleMap = L.tileLayer('http://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
    maxZoom: 24,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
}).addTo(map);

var vetinh = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 24,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

var nen = L.tileLayer.wms('https://nongdanviet.net/geoserver/total_feeling/wms', {
    layers: 'total_feeling:orthor_4326_chenhvenh',
    format: 'image/png',
    transparent: true,
    maxZoom: 22
});

L.control.layers(
    { "ggMap": googleMap, "Vệ tinh": vetinh },
    { "Nền bay chụp": nen }
).addTo(map);

// Biến trạng thái
let lastLatLng = null;
let isManualPosition = false;

// Tạo marker
var icon = L.icon({
    iconUrl: 'https://auth.hcmgis.vn/uploads/icon/icons8-map-marker-96.png',
    iconSize: [40, 40],
    iconAnchor: [20, 20],
    popupAnchor: [0, -48],
});

var marker = L.marker([
    <?= ($model->lat != null) ? $model->lat : 16.711630360842783 ?>,
    <?= ($model->long != null) ? $model->long : 106.63085460662843 ?>
], {
    draggable: true,
    icon: icon
}).addTo(map);

// Cập nhật vị trí khi kéo
marker.on('dragend', function(event) {
    const position = event.target.getLatLng();
    map.panTo(position);
    $('#geoy-input').val(position.lat);
    $('#geox-input').val(position.lng);
    isManualPosition = true;
});

// Nút định vị
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
        watch: false
    },
    clickBehavior: {
        inView: 'stop',
        outOfView: 'setView',
        inViewNotFollowing: 'setView'
    }
}).addTo(map);

// Fix cho điện thoại: touchstart
setTimeout(() => {
    const btn = document.querySelector('.leaflet-control-locate a');
    if (btn) {
        btn.addEventListener('touchstart', function (e) {
            e.preventDefault();
            btn.click();
        });
    }
}, 1000);

// Xử lý sau khi định vị
map.on("locationfound", function(e) {
    if (isManualPosition) return;

    const current = L.latLng(e.latitude, e.longitude);
    if (!lastLatLng || current.distanceTo(lastLatLng) > 5) {
        lastLatLng = current;
        $('#geoy-input').val(e.latitude);
        $('#geox-input').val(e.longitude);
        marker.setLatLng(current);
        map.setView(current, 18);
    }
});

\
function resetToGPS() {
    isManualPosition = false;
    map.locate({ setView: true, maxZoom: 18, enableHighAccuracy: true });
}

</script>