<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trình xem mô hình 3D - khu2_7a</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background-color: #f0f0f0;
            overflow: hidden; /* Ngăn cuộn trang */
        }
        #model-container {
            width: 100vw;
            height: 100vh;
            display: block;
        }
        #loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 100;
        }
        #loading-text {
            font-size: 1.2em;
            color: #333;
        }
        #progress-bar {
            width: 300px;
            height: 20px;
            background-color: #ddd;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        #progress-bar-inner {
            width: 0%;
            height: 100%;
            background-color: #4CAF50;
            transition: width 0.3s;
        }
        .toolbar {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            display: flex;
            gap: 5px;
            z-index: 10;
        }
        .toolbar button {
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.2s;
            color: #333;
        }
        .toolbar button:hover {
            background-color: #e0e0e0;
        }
        .toolbar button.active {
            background-color: #007bff;
            color: white;
        }
        .annotation-label {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            pointer-events: none; /* Cho phép click xuyên qua label */
        }
    </style>
</head>
<body>
    <div id="loading-overlay">
        <div id="loading-text">Đang tải mô hình...</div>
        <div id="progress-bar">
            <div id="progress-bar-inner"></div>
        </div>
    </div>

    <!-- Thanh công cụ -->
    <div id="toolbar" class="toolbar">
        <button id="translate-btn" title="Di chuyển (W)"><i class="fa-solid fa-up-down-left-right"></i></button>
        <button id="rotate-btn" title="Xoay (E)"><i class="fa-solid fa-rotate"></i></button>
        <button id="scale-btn" title="Tỷ lệ (R)"><i class="fa-solid fa-maximize"></i></button>
        <button id="measure-btn" title="Đo khoảng cách (M)"><i class="fa-solid fa-ruler"></i></button>
        <button id="fullscreen-btn" title="Toàn màn hình (F)"><i class="fa-solid fa-expand"></i></button>
    </div>

    <div id="model-container"></div>

    <script type="importmap">
        {
            "imports": {
                "three": "https://cdn.jsdelivr.net/npm/three@0.166.1/build/three.module.js",
                "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.166.1/examples/jsm/",
                "lil-gui": "https://cdn.jsdelivr.net/npm/lil-gui@0.19.2/dist/lil-gui.esm.js"
            }
        }
    </script>

    <script type="module">
        import * as THREE from 'three';
        import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
        import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
        import { TransformControls } from 'three/addons/controls/TransformControls.js';
        import { CSS2DRenderer, CSS2DObject } from 'three/addons/renderers/CSS2DRenderer.js';
        import GUI from 'lil-gui';

        // --- KHỞI TẠO CÁC BIẾN CƠ BẢN ---
        let scene, camera, renderer, labelRenderer, orbitControls, transformControls, loadedModel;
        let isMeasureMode = false;
        const measurePoints = [];
        const raycaster = new THREE.Raycaster();
        const pointer = new THREE.Vector2();
        
        // --- KHỞI TẠO SCENE ---
        function init() {
            // Scene
            scene = new THREE.Scene();
            scene.background = new THREE.Color(0xf0f0f0);

            // Camera
            camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            camera.position.set(10, 10, 10);

            // Renderer
            renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.localClippingEnabled = true; // Bật clipping
            document.getElementById('model-container').appendChild(renderer.domElement);

            // Label Renderer (for annotations)
            labelRenderer = new CSS2DRenderer();
            labelRenderer.setSize(window.innerWidth, window.innerHeight);
            labelRenderer.domElement.style.position = 'absolute';
            labelRenderer.domElement.style.top = '0px';
            labelRenderer.domElement.style.pointerEvents = 'none';
            document.getElementById('model-container').appendChild(labelRenderer.domElement);

            // Ánh sáng
            const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
            scene.add(ambientLight);
            const directionalLight = new THREE.DirectionalLight(0xffffff, 2.0);
            directionalLight.position.set(5, 10, 7.5);
            scene.add(directionalLight);

            // Điều khiển Orbit
            orbitControls = new OrbitControls(camera, renderer.domElement);
            orbitControls.enableDamping = true;
            orbitControls.dampingFactor = 0.05;

            // Tải mô hình
            loadModel();

            // Khởi tạo các công cụ
            initTools(ambientLight, directionalLight);

            // Vòng lặp render
            animate();
            
            // Bắt sự kiện
            window.addEventListener('resize', onWindowResize);
            renderer.domElement.addEventListener('click', onPointerClick);
            renderer.domElement.addEventListener('dblclick', onDoubleClick);
        }

        // --- TẢI MÔ HÌNH ---
        function loadModel() {
            const loadingManager = new THREE.LoadingManager();
            const loader = new GLTFLoader(loadingManager);
            const loadingOverlay = document.getElementById('loading-overlay');
            const progressBar = document.getElementById('progress-bar-inner');

            loadingManager.onProgress = (url, itemsLoaded, itemsTotal) => {
                progressBar.style.width = (itemsLoaded / itemsTotal) * 100 + '%';
            };
            loadingManager.onLoad = () => {
                loadingOverlay.style.display = 'none';
            };
            
            const modelPath = "<?= Yii::$app->homeUrl ?>resources/glb/khu2_7a.glb";
            loader.load(modelPath, (gltf) => {
                loadedModel = gltf.scene;
                
                const box = new THREE.Box3().setFromObject(loadedModel);
                const center = box.getCenter(new THREE.Vector3());
                const size = box.getSize(new THREE.Vector3());
                const maxDim = Math.max(size.x, size.y, size.z);
                const fov = camera.fov * (Math.PI / 180);
                let cameraZ = Math.abs(maxDim / 2 / Math.tan(fov / 2));
                cameraZ *= 1.5; 
                
                camera.position.set(center.x, center.y, center.z + cameraZ);
                orbitControls.target.copy(center);
                
                scene.add(loadedModel);
                transformControls.attach(loadedModel); // Gắn transform controls vào model
                console.log('Tải mô hình thành công!');
            }, undefined, (error) => {
                console.error('Lỗi khi tải mô hình:', error);
                document.getElementById('loading-text').innerText = 'Lỗi khi tải mô hình.';
            });
        }

        // --- KHỞI TẠO CÁC CÔNG CỤ TƯƠNG TÁC ---
        function initTools(ambientLight, directionalLight) {
            // Transform Controls
            transformControls = new TransformControls(camera, renderer.domElement);
            transformControls.addEventListener('dragging-changed', (event) => {
                orbitControls.enabled = !event.value;
            });
            scene.add(transformControls);
            transformControls.visible = false;
            transformControls.enabled = false;

            // GUI
            const gui = new GUI();
            const lightFolder = gui.addFolder('Ánh sáng');
            lightFolder.add(ambientLight, 'intensity', 0, 5, 0.1).name('Môi trường');
            lightFolder.add(directionalLight, 'intensity', 0, 5, 0.1).name('Hướng');
            lightFolder.addColor(directionalLight, 'color').name('Màu hướng');

            const modelFolder = gui.addFolder('Mô hình');
            const modelParams = { wireframe: false };
            modelFolder.add(modelParams, 'wireframe').name('Khung lưới').onChange((value) => {
                loadedModel.traverse((child) => {
                    if (child.isMesh) {
                        child.material.wireframe = value;
                    }
                });
            });

            // Clipping
            const clippingPlane = new THREE.Plane(new THREE.Vector3(0, -1, 0), 20);
            const clippingFolder = gui.addFolder('Mặt cắt');
            const clipParams = { enabled: false, constant: 20 };
            clippingFolder.add(clipParams, 'enabled').name('Bật/Tắt').onChange((value) => {
                renderer.clippingPlanes = value ? [clippingPlane] : [];
            });
            clippingFolder.add(clipParams, 'constant', -20, 20, 0.1).name('Vị trí').onChange((value) => {
                clippingPlane.constant = value;
            });

            // Gán sự kiện cho các nút trên toolbar
            document.getElementById('translate-btn').addEventListener('click', () => setTransformMode('translate'));
            document.getElementById('rotate-btn').addEventListener('click', () => setTransformMode('rotate'));
            document.getElementById('scale-btn').addEventListener('click', () => setTransformMode('scale'));
            document.getElementById('measure-btn').addEventListener('click', toggleMeasureMode);
            document.getElementById('fullscreen-btn').addEventListener('click', toggleFullscreen);

            // Phím tắt
            window.addEventListener('keydown', (event) => {
                switch (event.key.toLowerCase()) {
                    case 'w': setTransformMode('translate'); break;
                    case 'e': setTransformMode('rotate'); break;
                    case 'r': setTransformMode('scale'); break;
                    case 'm': toggleMeasureMode(); break;
                    case 'f': toggleFullscreen(); break;
                    case 'escape': deactivateAllModes(); break;
                }
            });
        }
        
        // --- CÁC HÀM XỬ LÝ CHẾ ĐỘ ---
        function setTransformMode(mode) {
            deactivateAllModes(true);
            transformControls.setMode(mode);
            transformControls.enabled = true;
            transformControls.visible = true;
            document.getElementById(mode + '-btn').classList.add('active');
        }

        function toggleMeasureMode() {
            deactivateAllModes(true);
            isMeasureMode = !isMeasureMode;
            document.getElementById('measure-btn').classList.toggle('active', isMeasureMode);
            if (!isMeasureMode) {
                clearMeasurements();
            }
        }

        function deactivateAllModes(keepActiveButton = false) {
            transformControls.enabled = false;
            transformControls.visible = false;
            if (!keepActiveButton || !isMeasureMode) {
                 isMeasureMode = false;
                 clearMeasurements();
            }
           
            document.querySelectorAll('.toolbar button').forEach(btn => {
                if(isMeasureMode && btn.id === 'measure-btn') return;
                btn.classList.remove('active');
            });
        }

        // --- HÀM ĐO ĐẠC ---
        function onPointerClick(event) {
            if (!isMeasureMode || !loadedModel) return;

            pointer.x = (event.clientX / window.innerWidth) * 2 - 1;
            pointer.y = -(event.clientY / window.innerHeight) * 2 + 1;
            raycaster.setFromCamera(pointer, camera);

            const intersects = raycaster.intersectObject(loadedModel, true);
            if (intersects.length > 0) {
                const point = intersects[0].point;
                measurePoints.push(point);

                // Vẽ điểm đánh dấu
                const dotGeometry = new THREE.SphereGeometry(0.05, 16, 16);
                const dotMaterial = new THREE.MeshBasicMaterial({ color: 0xff0000 });
                const dot = new THREE.Mesh(dotGeometry, dotMaterial);
                dot.position.copy(point);
                dot.name = "measure_dot";
                scene.add(dot);

                if (measurePoints.length === 2) {
                    // Vẽ đường thẳng
                    const lineGeometry = new THREE.BufferGeometry().setFromPoints(measurePoints);
                    const lineMaterial = new THREE.LineBasicMaterial({ color: 0xff0000, linewidth: 2 });
                    const line = new THREE.Line(lineGeometry, lineMaterial);
                    line.name = "measure_line";
                    scene.add(line);

                    // Hiển thị khoảng cách
                    const distance = measurePoints[0].distanceTo(measurePoints[1]);
                    const midPoint = new THREE.Vector3().addVectors(measurePoints[0], measurePoints[1]).multiplyScalar(0.5);
                    
                    const textDiv = document.createElement('div');
                    textDiv.className = 'annotation-label';
                    textDiv.textContent = `${distance.toFixed(2)} m`;
                    const distanceLabel = new CSS2DObject(textDiv);
                    distanceLabel.position.copy(midPoint);
                    distanceLabel.name = "measure_label";
                    scene.add(distanceLabel);

                    measurePoints.length = 0; // Reset để đo cặp tiếp theo
                }
            }
        }
        
        function clearMeasurements() {
             const objectsToRemove = [];
             scene.traverse(child => {
                if (child.name === "measure_dot" || child.name === "measure_line" || child.name === "measure_label") {
                    objectsToRemove.push(child);
                }
             });
             objectsToRemove.forEach(obj => scene.remove(obj));
        }

        // --- HÀM CHÚ THÍCH ---
        function onDoubleClick(event) {
            if (!loadedModel) return;
            
            pointer.x = (event.clientX / window.innerWidth) * 2 - 1;
            pointer.y = -(event.clientY / window.innerHeight) * 2 + 1;
            raycaster.setFromCamera(pointer, camera);

            const intersects = raycaster.intersectObject(loadedModel, true);
            if (intersects.length > 0) {
                 const point = intersects[0].point;
                 const note = prompt("Nhập nội dung chú thích:");
                 if (note) {
                    const textDiv = document.createElement('div');
                    textDiv.className = 'annotation-label';
                    textDiv.textContent = note;
                    const annotationLabel = new CSS2DObject(textDiv);
                    annotationLabel.position.copy(point);
                    scene.add(annotationLabel);
                 }
            }
        }

        // --- CÁC HÀM TIỆN ÍCH KHÁC ---
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }

        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
            labelRenderer.setSize(window.innerWidth, window.innerHeight);
        }

        function animate() {
            requestAnimationFrame(animate);
            orbitControls.update();
            renderer.render(scene, camera);
            labelRenderer.render(scene, camera);
        }

        // --- BẮT ĐẦU CHẠY ---
        init();
    </script>
</body>
</html>
