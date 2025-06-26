<?php

use http\SessionManager;

$session = SessionManager::getInstance();

if ($session->has('user')) : ?>
    <nav class="sticky bottom-0 w-full bg-sky-400 px-3 py-2">
        <div class="flex justify-between items-center px-4">
            <a href="/" class="text-white px-1 rounded focus:ring-2 focus:ring-white"><i class="fa-solid fa-house"></i></a>
            <a href="/search" class="text-white px-1 rounded focus:ring-2 focus:ring-white"><i class="fa-solid fa-magnifying-glass"></i></a>
            <a href="/new-post" class="text-white px-1 rounded focus:ring-2 focus:ring-white"><i class="fa-solid fa-square-plus"></i></a>
            <a href="/profile" class="text-white px-1 rounded focus:ring-2 focus:ring-white"><i class="fa-solid fa-user"></i></a>
        </div>
    </nav>
<?php endif; ?>