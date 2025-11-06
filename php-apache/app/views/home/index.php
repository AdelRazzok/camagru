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

<main data-user-logged="<?= $session->has('user') ? '1' : '0' ?>">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <?php if (empty($images)): ?>
            <div class="text-center py-16">
                <p class="text-gray-500 text-lg">No posts yet.</p>
            </div>
        <?php else: ?>
            <div class="space-y-8">
                <?php foreach ($images as $post): ?>
                    <?php
                        $image = $post['image'];
                        $image_id = $image->getId();
                        $file_path = htmlspecialchars($post['file_path']);
                        $author = htmlspecialchars($post['author']);
                        $created_at = htmlspecialchars($post['created_at']);
                        $like_count = $post['like_count'];
                        $user_liked = $post['user_liked'];
                        $comment_count = $post['comment_count'];
                    ?>

                    <article
                        class="bg-white rounded-lg shadow-md overflow-hidden"
                        data-image-id="<?= $image_id ?>"
                        data-like-count="<?= $like_count ?>"
                        data-user-liked="<?= $user_liked ? '1' : '0' ?>"
                        data-author="<?= $author ?>"
                        data-created-at="<?= $created_at ?>"
                        data-comment-count="<?= $comment_count ?>">

                        <div class="flex items-center justify-between px-4 py-3 rounded-t-lg">
                            <p class="font-semibold text-sm">
                                <?= $author ?>
                            </p>

                            <?php if ($post['user_is_owner']): ?>
                                <button
                                    class="delete-post-btn text-red-500 text-xl hover:text-red-700"
                                    data-image-id="<?php echo $post['image']->getId(); ?>"
                                    title="Delete Post"
                                >✕</button>
                            <?php endif; ?>
                        </div>

                        <div class="aspect-square bg-gray-100">
                            <img
                                src="<?= $file_path ?>"
                                alt="Post"
                                class="w-full h-full object-cover">
                        </div>

                        <div class="p-4 border-b">
                            <div class="flex gap-4 mb-3">
                                <button class="like-button text-2xl" data-image-id="<?= $image_id ?>">
                                    <span class="like-icon"><?= $user_liked ? '❤️' : '🤍' ?></span>
                                    <span class="like-count text-lg"><?= $like_count ?></span>
                                </button>
                                <button class="toggle-comments-btn text-2xl" data-image-id="<?= $image_id ?>">
                                    <span class="comment-icon">💬</span>
                                    <span class="comment-count text-lg"><?= $comment_count ?></span>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2"><?= $created_at ?></p>
                        </div>

                        <div class="comments-section hidden" id="comments-section-<?= $image_id ?>">
                            <div id="comments-<?= $image_id ?>" class="space-y-3 mb-4 max-h-48 overflow-y-auto hidden px-4"></div>

                            <?php if ($session->has('user')): ?>
                                <div class="p-4 border-t">
                                    <form class="add-comment-form" data-image-id="<?= $image_id ?>">
                                        <div class="flex gap-2">
                                            <input
                                                type="text"
                                                name="content"
                                                placeholder="Add a comment..."
                                                maxlength="500"
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                                                required>
                                            <button type="submit" class="bg-sky-500 text-white px-4 py-2 rounded text-sm hover:bg-sky-600">
                                                Send
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center items-center gap-2 mt-8">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>" class="px-4 py-2 bg-sky-500 text-white rounded hover:bg-sky-600">
                            ← Previous
                        </a>
                    <?php endif; ?>

                    <div class="flex gap-1">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $currentPage): ?>
                                <span class="px-3 py-2 bg-sky-500 text-white rounded font-semibold"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?>" class="px-3 py-2 border border-sky-500 text-sky-500 rounded hover:bg-sky-500 hover:text-white">
                                    <?= $i ?>
                                </a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>" class="px-4 py-2 bg-sky-500 text-white rounded hover:bg-sky-600">
                            Next →
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</main>

<script src="/public/js/toast.js"></script>
<script src="/public/js/likes.js"></script>
<script src="/public/js/comments.js"></script>
<script src="/public/js/posts.js"></script>

<?php
require_once dirname(__DIR__) . '/layouts/navbar.php';
require_once dirname(__DIR__) . '/layouts/footer.php';
?>
