<?php
require_once dirname(__DIR__) . '/layouts/header.php';

$inputClasses = function ($field) use ($errors) {
    return 'border ' . (!empty($errors[$field]) ? 'border-red-500' : 'border-gray-300') . ' rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500';
};
?>

<main class="flex min-h-svh w-full items-center justify-center p-6 md:p-10 bg-gray-100">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
        <a href="/">
            <h1 class="text-2xl text-center mb-8 playwrite-be-vlg-400">Camagru</h1>
        </a>

        <form action="/signup" method="post" class="flex flex-col">
            <div class="flex flex-col">
                <label for="email" class="text-sm text-gray-600 font-semibold mb-1">Email</label>
                <input type="email" name="email" placeholder="john.doe@example.com" class="<?= $inputClasses('email') ?>" value="<?= $old['email'] ?? '' ?>" required>

                <?php if (!empty($errors['email'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="flex flex-col mt-4">
                <label for="username" class="text-sm text-gray-600 font-semibold mb-1">Username</label>
                <input type="text" name="username" placeholder="john_doe" class="<?= $inputClasses('username') ?>" value="<?= $old['username'] ?? '' ?>" required>

                <?php if (!empty($errors['username'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['username'] ?></p>
                <?php endif; ?>
            </div>

            <div class="flex flex-col mt-4">
                <label for="password" class="text-sm text-gray-600 font-semibold mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="**********" class="w-full <?= $inputClasses('password') ?>" required>
                    <span id="toggleVisibilityIcon" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 cursor-pointer"><i class="fa-solid fa-eye"></i></span>
                </div>

                <?php if (!empty($errors['password'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['password'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="bg-sky-500 text-white mt-6 py-2 px-4 rounded hover:bg-sky-600 transition duration-200">Sign up</button>
        </form>

        <div class="text-center mt-6 text-sm text-gray-600">
            <p>Already have an account? <a href="/login" class="text-blue-600 hover:underline">Log in</a>.</p>
        </div>
    </div>
</main>

<script src="/public/js/login.js"></script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
