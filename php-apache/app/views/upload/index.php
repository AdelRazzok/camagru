<?php
$session = http\SessionManager::getInstance();
require_once dirname(__DIR__) . '/layouts/header.php';
?>

<div class="flex justify-between items-center p-4 shadow-md">
    <h1 class="text-2xl playwrite-be-vlg-400">Camagru</h1>

    <?php if ($session->has('user')): ?>
        <a href="/logout" class="text-xl"><i class="fa-solid fa-power-off"></i></a>
    <?php else: ?>
        <div>
            <a href="/login" class="bg-sky-500 text-white px-6 py-2 rounded focus:ring-2 focus:ring-blue-300">Login</a>
            <a href="/signup" class="bg-transparent hover:bg-sky-500 text-sky-500 hover:text-white py-2 px-4 border border-sky-500 hover:border-transparent rounded">Sign up</a>
        </div>
    <?php endif; ?>
</div>

<main class="flex min-h-svh w-full items-center justify-center p-6 md:p-10 bg-gray-100">
    <?php if (isset($error) && !empty($error)): ?>
        <div id="toast-error" class="fixed top-4 left-1/2 -translate-x-1/2 w-[90%] md:left-auto md:right-4 md:translate-x-0 md:w-auto bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md z-50">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p class="text-sm md:pr-3"><?= $error ?></p>
            </div>
            <button type="button" class="absolute top-0 right-1 text-red-700" onclick="closeToast('toast-error')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-2xl">
        <h2 class="text-2xl text-center mb-3 playwrite-be-vlg-400">Create your post</h2>

        <div class="flex gap-4 mb-8 border-b">
            <button
                id="tab-camera"
                class="tab-button px-4 py-2 font-semibold text-sky-500 border-b-2 border-sky-500 transition"
                data-tab="camera">
                <i class="fa-solid fa-camera mr-2"></i>Camera
            </button>
            <button
                id="tab-upload"
                class="tab-button px-4 py-2 font-semibold text-gray-600 border-b-2 border-transparent hover:text-sky-500 transition"
                data-tab="upload">
                <i class="fa-solid fa-upload mr-2"></i>Upload
            </button>
        </div>

        <div id="camera-tab" class="tab-content">
            <div class="flex flex-col gap-6">
                <video id="webcam" class="w-full rounded-lg bg-black" playsinline></video>
                <button
                    id="capture-btn"
                    class="w-full bg-sky-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-sky-600 transition">
                    <i class="fa-solid fa-circle mr-2"></i>Capture
                </button>
            </div>
        </div>

        <div id="upload-tab" class="tab-content hidden">
            <form action="/upload" method="post" enctype="multipart/form-data" class="flex flex-col gap-6">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-sky-500 hover:bg-sky-50 transition">
                    <label for="image" class="cursor-pointer">
                        <div class="flex flex-col items-center gap-3">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Click to upload or drag and drop</p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF, WebP (max 5MB)</p>
                            </div>
                        </div>
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                            class="hidden"
                            required>
                    </label>
                </div>

                <div id="sticker-preview" class="hidden">
                    <div class="flex flex-col gap-4">
                        <canvas id="preview-canvas" class="w-full rounded-lg bg-black" style="max-height: 500px;"></canvas>

                        <div class="flex justify-between items-center">
                            <button id="prev-sticker" class="bg-sky-400 text-white px-4 py-2 rounded hover:bg-sky-500">◄</button>
                            <span id="sticker-info" class="font-semibold text-gray-700">Sticker 1/5</span>
                            <button id="next-sticker" class="bg-sky-400 text-white px-4 py-2 rounded hover:bg-sky-500">►</button>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" id="change-image" class="flex-1 bg-red-500 text-white py-2 px-4 rounded-lg font-semibold hover:bg-red-600 transition">
                                <i class="fa-solid fa-xmark mr-2"></i>Cancel
                            </button>
                            <button type="submit" class="flex-1 bg-sky-400 text-white py-2 px-4 rounded-lg font-semibold hover:bg-sky-500 transition">
                                <i class="fa-solid fa-check mr-2"></i>Validate
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="/public/js/toast.js"></script>
<script src="/public/js/upload.js"></script>

<?php require_once dirname(__DIR__) . '/layouts/navbar.php'; ?>
<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>