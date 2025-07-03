<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Total Feeling</title>

    <!-- Thư viện CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Thư viện JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #f0f2f5;
        }

        .dashboard-container {
            padding: 2rem;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #ffffff;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card .card-body {
            display: flex;
            align-items: center;
            padding: 1.5rem;
        }

        .stat-card .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            flex-shrink: 0;
        }

        .stat-card .icon-circle i {
            font-size: 1.75rem;
            color: #fff;
        }
        
        .chart-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            height: 100%;
        }

        .chart-container {
            height: 350px;
            width: 100%;
        }

        #map {
            height: 500px;
            border-radius: 12px;
            width: 100%;
        }
        
        .main-title {
            font-weight: 700;
            color: #343a40;
        }
        
        .card-title {
             font-weight: 600;
             color: #495057;
margin-bottom: 1rem;
        }

        /* Màu sắc cho các thẻ */
        .bg-primary-light { background-color: rgba(59, 130, 246, 0.1); color: #3B82F6; }
        .bg-success-light { background-color: rgba(34, 197, 94, 0.1); color: #22C55E; }
        .bg-warning-light { background-color: rgba(245, 158, 11, 0.1); color: #F59E0B; }
        .bg-info-light { background-color: rgba(14, 165, 233, 0.1); color: #0EA5E9; }
        .bg-danger-light { background-color: rgba(239, 68, 68, 0.1); color: #EF4444; }
        .bg-dark-light { background-color: rgba(55, 65, 81, 0.1); color: #374151; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="mb-4 main-title">Dashboard Total Feeling</h1>

        <!-- Thẻ Thống Kê -->
        <div class="row g-4 mb-5">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="icon-circle bg-primary-light"><i class="fas fa-mug-hot"></i></div>
                        <div>
                            <h6 class="text-muted mb-1">Cà phê</h6>
                            <h4 class="fw-bold mb-0"><?= $thongke['CayCaPhe'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="icon-circle bg-success-light"><i class="fas fa-seedling"></i></div>
                        <div>
                            <h6 class="text-muted mb-1">Cây chuối</h6>
                            <h4 class="fw-bold mb-0"><?= $thongke['CayChuoi'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="icon-circle bg-warning-light"><i class="fas fa-leaf"></i></div>
                        <div>
                            <h6 class="text-muted mb-1">Gáo vàng</h6>
                            <h4 class="fw-bold mb-0"><?= $thongke['CayGaoVang'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card">
                     <div class="card-body">
                        <div class="icon-circle bg-info-light"><i class="fas fa-fan"></i></div>
                        <div>
                            <h6 class="text-muted mb-1">Ngân hoa</h6>
                            <h4 class="fw-bold mb-0"><?= $thongke['CayNganHoa'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
<div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="icon-circle bg-danger-light"><i class="fas fa-tree"></i></div>
                        <div>
                            <h6 class="text-muted mb-1">Cây sưa</h6>
                            <h4 class="fw-bold mb-0"><?= $thongke['CaySenKhac'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="icon-circle bg-dark-light"><i class="fas fa-layer-group"></i></div>
                        <div>
                            <h6 class="text-muted mb-1">Tổng số</h6>
                            <h4 class="fw-bold mb-0"><?= $thongke['CaySenKhac'] + $thongke['CayNganHoa']+ $thongke['CayGaoVang']+ $thongke['CayChuoi']+ $thongke['CayCaPhe'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hàng biểu đồ 1 -->
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="chart-card">
                    <h5 class="card-title">Tỷ lệ các loại cây</h5>
                    <div class="chart-container">
                        <canvas id="treeRatioChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="chart-card">
                    <h5 class="card-title">Sản lượng thu hoạch (Tấn)</h5>
                    <div class="chart-container">
                        <canvas id="harvestChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hàng biểu đồ 2 -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="chart-card">
                    <h5 class="card-title">Tình trạng sức khỏe cây trồng (12 tháng)</h5>
                    <div id="healthStatusChart" class="chart-container"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-card">
                    <h5 class="card-title">So sánh chỉ số tăng trưởng</h5>
                    <div id="growthRadarChart" class="chart-container"></div>
                </div>
            </div>
        </div>

        <!-- Bản đồ -->
        <!-- <div class="row g-4">
            <div class="col-12">
                <div class="chart-card">
                    <h5 class="card-title">Bản đồ phân bố cây trồng</h5>
                    <div id="map"></div>
                </div>
            </div>
        </div> -->
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Dữ liệu mẫu
    const treeData = {
        labels: ['Cà phê', 'Chuối', 'Gáo vàng', 'Ngân hoa', 'Sưa'],
        values: [<?=$thongke['CayCaPhe']?>,<?= $thongke['CayChuoi']?>,<?= $thongke['CayGaoVang']?>,<?= $thongke['CayNganHoa']?>, <?= $thongke['CaySenKhac'] ?>]
    };

    const harvestData = {
        labels: ['Thg 1', 'Thg 2', 'Thg 3', 'Thg 4', 'Thg 5', 'Thg 6'],
        datasets: [
            {
                label: 'Cà phê',
                data: [12, 19, 15, 22, 18, 25],
                backgroundColor: '#3B82F6',
            },
            {
                label: 'Chuối',
                data: [8, 12, 9, 14, 11, 16],
                backgroundColor: '#22C55E',
            }
        ]
    };

    const healthData = {
        months: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        good: [85, 88, 90, 86, 82, 85, 88, 92, 93, 90, 88, 85],
        mild: [10, 8, 7, 9, 12, 10, 8, 5, 4, 6, 8, 10],
        severe: [5, 4, 3, 5, 6, 5, 4, 3, 3, 4, 4, 5]
    };

    // 1. Biểu đồ tỷ lệ cây (Donut Chart - Chart.js)
    const treeRatioCtx = document.getElementById('treeRatioChart').getContext('2d');
    new Chart(treeRatioCtx, {
        type: 'doughnut',
        data: {
            labels: treeData.labels,
            datasets: [{
                label: 'Số lượng cây',
                data: treeData.values,
                backgroundColor: ['#3B82F6', '#22C55E', '#F59E0B', '#0EA5E9', '#EF4444'],
                borderColor: '#fff',
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // 2. Biểu đồ sản lượng (Bar Chart - Chart.js)
    const harvestCtx = document.getElementById('harvestChart').getContext('2d');
    new Chart(harvestCtx, {
        type: 'bar',
        data: harvestData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            textStyle: {
                fontFamily: 'Roboto'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e9ecef'
                    }
                },
                x: {
                   grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            borderRadius: 5,
        }
    });

    // 3. Biểu đồ tình trạng sức khỏe (Area Chart - ECharts)
    const healthStatusChart = echarts.init(document.getElementById('healthStatusChart'));
    const healthOption = {
        textStyle: {
                fontFamily: 'Roboto'
            },
        tooltip: { trigger: 'axis' },
legend: { data: ['Tốt', 'Bệnh nhẹ', 'Bệnh nặng'] },
        grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
        xAxis: { type: 'category', boundaryGap: false, data: healthData.months },
        yAxis: { type: 'value', axisLabel: { formatter: '{value} %' } },
        series: [
            { name: 'Tốt', type: 'line', stack: 'Total', areaStyle: {}, emphasis: { focus: 'series' }, data: healthData.good, color: '#22C55E' },
            { name: 'Bệnh nhẹ', type: 'line', stack: 'Total', areaStyle: {}, emphasis: { focus: 'series' }, data: healthData.mild, color: '#F59E0B' },
            { name: 'Bệnh nặng', type: 'line', stack: 'Total', areaStyle: {}, emphasis: { focus: 'series' }, data: healthData.severe, color: '#EF4444' }
        ]
    };
    healthStatusChart.setOption(healthOption);

    // 4. Biểu đồ Radar (ECharts)
    const growthRadarChart = echarts.init(document.getElementById('growthRadarChart'));
    const radarOption = {
        textStyle: {
                fontFamily: 'Roboto'
            },
        legend: { data: ['Cà phê', 'Sưa'], bottom: 0 },
        radar: {
            indicator: [
                { name: 'Tỷ lệ ra hoa', max: 100 },
                { name: 'Kháng sâu bệnh', max: 100 },
                { name: 'Hấp thụ nước', max: 100 },
                { name: 'Tăng trưởng', max: 100 },
                { name: 'Chất lượng quả', max: 100 }
            ]
        },
        series: [{
            name: 'Chỉ số tăng trưởng',
            type: 'radar',
            data: [
                { value: [85, 90, 75, 80, 92], name: 'Cà phê' },
                { value: [70, 75, 85, 88, 78], name: 'Sưa' }
            ]
        }]
    };
    growthRadarChart.setOption(radarOption);
    
    // Đảm bảo ECharts thay đổi kích thước khi cửa sổ thay đổi
    window.addEventListener('resize', function() {
        healthStatusChart.resize();
        growthRadarChart.resize();
    });

});
</script>
</body>
</html>