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
        console.log('Requesting webcam access...');
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'user',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        });

        console.log('Access OK');
        video.srcObject = stream;

        console.log('Playing video stream...');
        video.addEventListener('loadedmetadata', () => {
            video.play();
        });
    } catch (error) {
        console.error('Error accessing webcam:', error);
    }
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
        stream = null;
    }
}

const fileInput = document.getElementById('image');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const fileName = document.getElementById('file-name');
const fileSize = document.getElementById('file-size');
const removeButton = document.getElementById('remove-image');

fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            previewImage.src = event.target.result;
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

removeButton.addEventListener('click', (e) => {
    e.preventDefault();
    fileInput.value = '';
    previewContainer.classList.add('hidden');
});

const dropZone = document.querySelector('[for="image"]').parentElement;

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-sky-500', 'bg-sky-50');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-sky-500', 'bg-sky-50');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-sky-500', 'bg-sky-50');
    fileInput.files = e.dataTransfer.files;
    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
});
