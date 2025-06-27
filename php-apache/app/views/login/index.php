<?php
$session = http\SessionManager::getInstance();
require_once dirname(__DIR__) . '/layouts/header.php';
?>

<main class="flex min-h-svh w-full items-center justify-center p-6 md:p-10 bg-gray-100">
    <?php if (isset($success) && !empty($success)): ?>
        <div id="toast-success" class="fixed top-4 left-1/2 -translate-x-1/2 w-[90%] md:left-auto md:right-4 md:translate-x-0 md:w-auto bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md z-50 transform transition-transform duration-300 ease-in-out">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i>
                <p class="text-sm md:pr-3"><?= $success ?></p>
            </div>

            <button type="button" class="absolute top-0 right-1 text-green-700" onclick="closeToast('toast-success')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if (isset($error) && !empty($error)): ?>
        <div id="toast-error" class="fixed top-4 left-1/2 -translate-x-1/2 w-[90%] md:left-auto md:right-4 md:translate-x-0 md:w-auto bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md z-50 transform transition-transform duration-300 ease-in-out">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p class="text-sm md:pr-3"><?= $error ?></p>
            </div>

            <button type="button" class="absolute top-0 right-1 text-red-700" onclick="closeToast('toast-error')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
        <a href="/">
            <h1 class="text-2xl text-center mb-8 playwrite-be-vlg-400">Camagru</h1>
        </a>

        <?php if ($session->getFlash('verification_needed')): ?>
            <div class="mb-6 p-4 rounded-lg bg-amber-50 border border-amber-200">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800">Account verification required</h3>
                        <p class="mt-1 text-sm text-amber-700">Please verify your account before logging in. We sent a verification link to your email address.</p>
                        <p class="mt-2 text-sm text-amber-700">
                            Didn't receive the email?
                            <a href="/resend-verification?username=<?= htmlspecialchars($session->getFlash('username')) ?>" class="font-medium text-amber-700 underline hover:text-amber-600">
                                Resend verification email
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form action="/login" method="post" class="flex flex-col">
            <div class="flex flex-col">
                <label for="username" class="text-sm text-gray-600 font-semibold mb-1">Username</label>
                <input type="text" name="username" placeholder="john_doe" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" value="<?= $old['username'] ?? '' ?>" required>
            </div>

            <div class="flex flex-col mt-4">
                <label for="password" class="text-sm text-gray-600 font-semibold mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="**********" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                    <span id="toggleVisibilityIcon" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 cursor-pointer"><i class="fa-solid fa-eye"></i></span>
                </div>
            </div>

            <button type="submit" class="bg-sky-500 text-white mt-6 py-2 px-4 rounded hover:bg-sky-600 transition duration-200">Login</button>
        </form>

        <div class="text-center mt-4">
            <a href="/reset-password" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
        </div>

        <div class="text-center mt-6 text-sm text-gray-600">
            <p>Don't have an account? <a href="/signup" class="text-blue-600 hover:underline">Register here</a>.</p>
        </div>
    </div>
</main>

<script src="/public/js/login.js"></script>
<script src="/public/js/toast.js"></script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>