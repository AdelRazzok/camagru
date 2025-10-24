<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-8">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>

                <h3 class="mt-3 text-lg font-medium text-gray-900">Error.</h3>

                <p class="mt-2 text-gray-500"><?= htmlspecialchars($error) ?></p>
            </div>

            <div class="mt-8 space-y-8">
                <?php if ($isExpired): ?>
                    <p class="text-gray-500 text-center">
                        <a href="/resend-verification" class="text-blue-600 hover:underline">
                            Click here
                        </a>
                        to resend the verification email.
                    </p>
                    <div class="text-center">
                        <a href="/login" class="bg-sky-500 text-white px-4 py-2 rounded focus:ring-2 focus:ring-blue-300">Login</a>
                    </div>
                <?php else: ?>
                    <div class="text-center space-y-4">
                        <div class="space-x-4">
                            <a href="/login" class="bg-sky-500 text-white px-6 py-2 rounded focus:ring-2 focus:ring-blue-300">Login</a>
                            <a href="/signup" class="bg-transparent hover:bg-sky-500 text-sky-500 hover:text-white py-2 px-4 border border-sky-500 hover:border-transparent rounded">Sign up</a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="text-center">
                    <a href="/" class="text-sm text-blue-600 hover:underline">Return to home page</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>