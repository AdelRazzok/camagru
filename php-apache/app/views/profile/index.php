<?php
$session = http\SessionManager::getInstance();
require_once dirname(__DIR__) . '/layouts/header.php';

$inputClasses = function ($field) use ($errors) {
    return 'border ' . (!empty($errors[$field]) ? 'border-red-500' : 'border-gray-300') . ' rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500';
};
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
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
        <h2 class="text-2xl text-center mb-3 playwrite-be-vlg-400">Profile</h2>

        <form action="/profile" method="post" class="flex flex-col">
            <div class="flex flex-col">
                <label for="email" class="text-sm text-gray-600 font-semibold mb-1">Email</label>
                <input type="email" name="email" placeholder="john.doe@example.com" class="<?= $inputClasses('email') ?>" value="<?= $user->getEmail() ?>" required>

                <?php if (!empty($errors['email'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="flex flex-col mt-4">
                <label for="username" class="text-sm text-gray-600 font-semibold mb-1">Username</label>
                <input type="text" name="username" placeholder="john_doe" class="<?= $inputClasses('username') ?>" value="<?= $user->getUsername() ?>" required>

                <?php if (!empty($errors['username'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['username'] ?></p>
                <?php endif; ?>
            </div>

            <div class="flex flex-col mt-4">
                <label for="password" class="text-sm text-gray-600 font-semibold mb-1">
                    Password
                    <span class="text-xs text-gray-500 font-normal ml-2">Leave blank to not change</span>
                </label>
                <input
                    type="password"
                    name="password"
                    placeholder="New password"
                    class="<?= $inputClasses('password') ?>">
                <?php if (!empty($errors['password'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['password'] ?></p>
                <?php endif; ?>
            </div>

            <div class="flex flex-col mt-4">
                <label class="flex items-center justify-between cursor-pointer">
                    <span class="text-sm text-gray-600 font-semibold">Email notifications for comments</span>

                    <div class="relative">
                        <input type="hidden" name="email_notif_on_comment" value="0">
                        <input
                            type="checkbox"
                            name="email_notif_on_comment"
                            value="1"
                            class="sr-only peer"
                            <?= $user->isEmailNotifOnComment() ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-sky-500 transition-colors"></div>
                        <div class="absolute top-0 left-0 w-6 h-6 bg-white border rounded-full transform peer-checked:translate-x-5 transition-transform shadow-sm"></div>
                    </div>
                </label>

                <?php if (!empty($errors['email_notif_on_comment'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['email_notif_on_comment'] ?></p>
                <?php endif; ?>
            </div>


            <button type="submit" class="bg-sky-500 text-white mt-6 py-2 px-4 rounded hover:bg-sky-600 transition duration-200">Update</button>
        </form>
    </div>
</main>

<?php
require_once dirname(__DIR__) . '/layouts/navbar.php';
require_once dirname(__DIR__) . '/layouts/footer.php';
?>
