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

<main>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <?php if (empty($images)): ?>
            <div class="text-center py-16">
                <p class="text-gray-500 text-lg">No images available</p>
            </div>
        <?php else: ?>
            <div class="space-y-8">
                <?php foreach ($images as $post): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-4 py-3 rounded-t-lg">
                            <p class="font-semibold text-sm">
                                <?= htmlspecialchars($post['author']) ?>
                            </p>
                        </div>

                        <div class="aspect-square bg-gray-100">
                            <img 
                                src="<?= htmlspecialchars($post['file_path']) ?>" 
                                alt="Post" 
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <div class="p-4 border-b">
                            <div class="flex gap-4 mb-3">
                                <button class="like-button text-2xl" data-image-id="<?= $post['image']->getId() ?>">
                                    <?= $post['user_liked'] ? '❤️' : '🤍' ?>
                                    <span class="text-lg"><?= $post['like_count'] ?></span>
                                </button>
                                <button class="text-2xl">💬</button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2"><?= $post['created_at'] ?></p>
                        </div>

                        <!-- Comments Section -->
                        <div class="p-4">
                            <div id="comments-<?= $post['image']->getId() ?>" class="space-y-3 mb-4 max-h-48 overflow-y-auto">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
require_once dirname(__DIR__) . '/layouts/navbar.php';
require_once dirname(__DIR__) . '/layouts/footer.php';
?>
