<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<main class="flex min-h-svh w-full items-center justify-center p-6 md:p-10 bg-gray-100">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
        <h1 class="text-2xl text-center mb-7 playwrite-be-vlg-400">Camagru</h1>
        <form action="#" method="post" class="flex flex-col space-y-4">
            <div class="flex flex-col">
                <label for="username" class="text-sm text-gray-600 font-semibold mb-1">Username</label>
                <input type="text" name="username" placeholder="john_doe" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
            </div>

            <div class="flex flex-col">
                <label for="password" class="text-sm text-gray-600 font-semibold mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="**********" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                    <span id="toggleVisibilityIcon" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 cursor-pointer"><i class="fa-solid fa-eye"></i></span>
                </div>
            </div>

            <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded hover:bg-sky-600 transition duration-200">Login</button>
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

<?php
require_once dirname(__DIR__) . '/layouts/footer.php';
?>
