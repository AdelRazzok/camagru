/* ==============================
    CONSTANTS
============================== */

const CANVAS_WIDTH = 800;
const CANVAS_HEIGHT = 600;
const STICKER_HANDLE_SIZE = 20;

const stickers = [
    'public/images/stickers/sticker_1.png',
    'public/images/stickers/sticker_2.png',
    'public/images/stickers/sticker_3.png',
    'public/images/stickers/sticker_4.png',
    'public/images/stickers/sticker_5.png',
];

/* ==============================
    DOM ELEMENTS
============================== */

const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');

const video = document.getElementById('webcam');
const captureBtn = document.getElementById('capture-btn');
const captureCanvas = document.getElementById('capture-canvas');
const captureCtx = captureCanvas?.getContext('2d');

const fileInput = document.getElementById('image');
const uploadZone = document.getElementById('upload-zone');

const canvas = document.getElementById('preview-canvas');
const ctx = canvas?.getContext('2d');
const stickerPreview = document.getElementById('sticker-preview');
const stickerInfo = document.getElementById('sticker-info');
const prevBtn = document.getElementById('prev-sticker');
const nextBtn = document.getElementById('next-sticker');
const changeImageBtn = document.getElementById('change-image');

/* ==============================
    STATE
============================== */

let stream = null;
let currentStickerIndex = 0;
let currentImage = null;
let currentMode = null;

let currentStickerX = 0;
let currentStickerY = 0;
let currentStickerScale = 1;
let isDragging = false;
let isResizing = false;
let dragStartX = 0;
let dragStartY = 0;

/* ==============================
    TAB MANAGEMENT
============================== */

tabButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const tabName = button.dataset.tab;

        if (stickerPreview && !stickerPreview.classList.contains('hidden')) {
            hideStickerPreview(currentMode);
        }

        tabButtons.forEach((btn) => {
            btn.classList.remove('text-sky-500', 'border-b-2', 'border-sky-500');
            btn.classList.add('text-gray-600', 'border-b-2', 'border-transparent');
        });

        button.classList.add('text-sky-500', 'border-b-2', 'border-sky-500');
        button.classList.remove('text-gray-600', 'border-b-2', 'border-transparent');

        tabContents.forEach((content) => {
            content.classList.add('hidden');
        });

        document.getElementById(`${tabName}-tab`).classList.remove('hidden');

        if (tabName === 'camera') {
            startCamera();
        } else if (tabName === 'upload') {
            stopCamera();
        }
    });
});

/* ==============================
    CAMERA
============================== */

async function startCamera() {
    if (stream !== null) {
        return;
    }

    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'user',
                width: { ideal: 1280 },
                height: { ideal: 720 },
            },
            audio: false,
        });
        video.srcObject = stream;
        video.addEventListener('loadedmetadata', () => {
            video.play();
        });
    } catch (error) {
        showErrorToast('Not able to access the webcam.');
    }
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
        stream = null;
        video.srcObject = null;
    }
}

if (captureBtn) {
    captureBtn.addEventListener('click', (e) => {
        e.preventDefault();

        if (!video.srcObject) {
            showErrorToast('Camera is not available.');
            return;
        }

        captureCanvas.width = video.videoWidth;
        captureCanvas.height = video.videoHeight;

        captureCtx.drawImage(video, 0, 0, captureCanvas.width, captureCanvas.height);
        
        currentImage = captureCanvas.toDataURL('image/png');
        currentStickerIndex = 0;

        showStickerPreview('camera');
        stopCamera();
    });
}

/* ==============================
    FILE UPLOAD
============================== */

if (uploadZone) {
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('border-sky-500', 'bg-sky-50');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('border-sky-500', 'bg-sky-50');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('border-sky-500', 'bg-sky-50');
        fileInput.files = e.dataTransfer.files;
        fileInput.dispatchEvent(new Event('change', { bubbles: true }));
    });
}

fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];

    if (file) {
        if (!file.type.startsWith('image/')) {
            showErrorToast('This file type is not supported.');
            fileInput.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            showErrorToast('File size exceeds 5MB limit.');
            fileInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onerror = () => {
            showErrorToast('Error reading the file.');
        };
        reader.onload = (event) => {
            currentImage = event.target.result;
            currentStickerIndex = 0;
            showStickerPreview('upload');
        };
        reader.readAsDataURL(file);
    }
});

/* ==============================
    STICKER PREVIEW
============================== */

function drawCanvas() {
    if (!currentImage || !ctx) return;

    const img = new Image();
    img.onerror = () => {
        showErrorToast('Error loading image.');
    };
    img.onload = () => {
        canvas.width = img.width;
        canvas.height = img.height;

        ctx.drawImage(img, 0, 0);

        const stickerImg = new Image();
        stickerImg.onerror = () => {
            console.error('Error loading sticker:', stickers[currentStickerIndex]);
        };
        stickerImg.onload = () => {
            const x = (canvas.width - stickerImg.width) / 2;
            const y = (canvas.height - stickerImg.height) / 2;
            ctx.drawImage(stickerImg, x, y);
        };
        stickerImg.src = stickers[currentStickerIndex];
    };
    img.src = currentImage;
}

function updateStickerInfo() {
    if (stickerInfo) {
        stickerInfo.textContent = `Sticker ${currentStickerIndex + 1}/${stickers.length}`;
    }
}

function showStickerPreview(mode) {
    currentMode = mode;

    if (mode === 'camera') {
        document.getElementById('webcam')?.classList.add('hidden');
        document.getElementById('capture-btn')?.classList.add('hidden');
    } else {
        document.getElementById('upload-form')?.classList.add('hidden');
    }

    // TODO: Reset sticker position and scale

    if (stickerPreview) stickerPreview.classList.remove('hidden');
    updateStickerInfo();
    drawCanvas();
}

function hideStickerPreview(mode) {

    if (mode === 'camera') {
        document.getElementById('webcam')?.classList.remove('hidden');
        document.getElementById('capture-btn')?.classList.remove('hidden');
    } else {
        document.getElementById('upload-form')?.classList.remove('hidden');
    }

    if (stickerPreview) stickerPreview.classList.add('hidden');
    currentImage = null;
    currentStickerIndex = 0;
    
    if (mode === 'camera') {
        startCamera();
    }
}

/* ==============================
    STICKER NAVIGATION
============================== */

if (prevBtn) {
    prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        currentStickerIndex = (currentStickerIndex - 1 + stickers.length) % stickers.length;
        updateStickerInfo();
        drawCanvas();
    });
}

if (nextBtn) {
    nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        currentStickerIndex = (currentStickerIndex + 1) % stickers.length;
        updateStickerInfo();
        drawCanvas();
    });
}

if (changeImageBtn) {
    changeImageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        fileInput.value = '';
        hideStickerPreview(currentMode);
    });
}

/* ==============================
    STICKER DRAG & RESIZE
============================== */


/* ==============================
    NOTIFICATIONS
============================== */

function showErrorToast(message) {
    const toast = document.createElement('div');
    toast.className =
        'fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md z-50';
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation"></i>
            <p>${message}</p>
        </div>
    `;
    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 5000);
}

/* ==============================
    INITIALIZATION
============================== */

startCamera();
