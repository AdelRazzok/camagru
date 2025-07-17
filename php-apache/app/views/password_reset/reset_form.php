<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">

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

  <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-8">
      <a href="/">
        <h1 class="text-2xl text-center mb-8 playwrite-be-vlg-400">Camagru</h1>
      </a>

      <p class="text-center text-gray-600 mb-6">
        Enter your email address and we'll send you a password reset link.
      </p>

      <form action="/reset-password" method="POST" class="mt-6">
        <div class="mb-4">
          <label for="password" class="text-sm text-gray-600 font-semibold mb-1">New password</label>
          <div class="relative">
            <input type="password" id="password" name="password" placeholder="**********" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
            <span id="toggleVisibilityIcon" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 cursor-pointer"><i class="fa-solid fa-eye"></i></span>
          </div>
        </div>

        <div class="mb-4">
          <label for="password_confirmation" class="text-sm text-gray-600 font-semibold mb-1">Confirmation</label>
          <div class="relative">
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="**********" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
          </div>
        </div>

        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>">

        <div class="flex justify-center mt-6">
          <button type="submit" class="bg-sky-500 text-white mt-2 py-2 px-4 rounded hover:bg-sky-600 transition duration-200">
            Reset password
          </button>
        </div>
      </form>

      <div class="mt-6 text-center">
        <a href="/login" class="text-sky-500 hover:underline text-sm">Return to login</a>
      </div>
    </div>
  </div>
</div>

<script src="/public/js/login.js"></script>
<script src="/public/js/toast.js"></script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>