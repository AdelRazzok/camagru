const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');

tabButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const tabName = button.dataset.tab;

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

const video = document.getElementById('webcam');
let stream = null;

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
        console.error('Error accessing webcam:', error);
        showErrorToast('Not able to access the webcam.');
    }
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
        stream = null;
    }
}

const fileInput = document.getElementById('image');
const uploadZone = document.getElementById('upload-zone');

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

const canvas = document.getElementById('preview-canvas');
const ctx = canvas?.getContext('2d');
const stickerPreview = document.getElementById('sticker-preview');
const stickerInfo = document.getElementById('sticker-info');
const prevBtn = document.getElementById('prev-sticker');
const nextBtn = document.getElementById('next-sticker');
const changeImageBtn = document.getElementById('change-image');

const stickers = [
    'public/images/stickers/sticker_1.png',
    'public/images/stickers/sticker_2.png',
    'public/images/stickers/sticker_3.png',
    'public/images/stickers/sticker_4.png',
    'public/images/stickers/sticker_5.png',
];

let currentStickerIndex = 0;
let currentImage = null;

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
        if (stickerPreview) stickerPreview.classList.add('hidden');
        if (uploadZone) uploadZone.classList.remove('hidden');
        currentImage = null;
        currentStickerIndex = 0;
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
            if (uploadZone) uploadZone.classList.add('hidden');
            if (stickerPreview) stickerPreview.classList.remove('hidden');
            currentStickerIndex = 0;
            updateStickerInfo();
            drawCanvas();
        };
        reader.readAsDataURL(file);
    }
});

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
