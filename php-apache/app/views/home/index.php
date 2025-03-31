<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="flex justify-between items-center p-4 shadow-md">
    <h1 class="text-2xl">Camagru</h1>
    <div>
        <a href="/login" class="bg-sky-500 text-white px-6 py-2 rounded focus:ring-2 focus:ring-blue-300">Login</a>
        <a href="/signup" class="bg-transparent hover:bg-sky-500 text-sky-500 hover:text-white py-2 px-4 border border-sky-500 hover:border-transparent rounded">Sign up</a>
    </div>
</div>

<main>

</main>

<?php
require_once dirname(__DIR__) . '/layouts/navbar.php';
require_once dirname(__DIR__) . '/layouts/footer.php';
?>