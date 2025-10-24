<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<main class="flex min-h-svh w-full flex-col items-center justify-center p-6 md:p-10 bg-gray-100">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md text-center">
        <div class="flex flex-col items-center">
            <h1 class="text-9xl font-bold text-sky-500">500</h1>

            <h2 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 playwrite-be-vlg-400">Internal Server Error</h2>

            <p class="mt-8 text-base leading-7 text-gray-600">Sorry, something went wrong on our end.</p>

            <div class="mt-8">
                <a href="/" class="bg-sky-500 text-white py-2 px-6 rounded hover:bg-sky-600 transition duration-200">Back to home</a>
            </div>
        </div>
    </div>
</main>

<?php
require_once dirname(__DIR__) . '/layouts/navbar.php';
require_once dirname(__DIR__) . '/layouts/footer.php';
?>
