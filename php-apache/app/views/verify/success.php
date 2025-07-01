<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-8">
      <div class="text-center">
        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>

        <?php if (isset($alreadyVerified) && $alreadyVerified): ?>
          <h3 class="mt-3 text-lg font-medium text-gray-900">Account already verified.</h3>
          <p class="mt-2 text-gray-500">Your account has already been verified.</p>
        <?php else: ?>
          <h3 class="mt-3 text-lg font-medium text-gray-900">Account verified successfully.</h3>
          <p class="mt-2 text-gray-500">Your email has been verified.</p>
        <?php endif; ?>
      </div>

      <div class="mt-8 text-center">
        <a href="/login" class="bg-sky-500 text-white mt-2 py-2 px-4 rounded hover:bg-sky-600 transition duration-200">Login</a>
      </div>

      <div class="mt-8 text-center">
        <a href="/" class="text-sm text-blue-600 hover:underline">Return to home</a>
      </div>
    </div>
  </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>