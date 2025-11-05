document.addEventListener('DOMContentLoaded', () => {
    const main = document.querySelector('main');
    const userLogged = main && main.dataset.userLogged === '1';
    const buttons = document.querySelectorAll('.like-button');

    buttons.forEach((btn) => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            if (!userLogged) {
                return;
            }

            const imageId = btn.dataset.imageId;
            if (!imageId) return;

            const icon = btn.querySelector('.like-icon');
            const countEl = btn.querySelector('.like-count');
            const wasLiked = btn.dataset.userLiked === '1';
            const previousCount = parseInt(countEl.textContent, 10) || 0;

            const newLiked = !wasLiked;
            btn.dataset.userLiked = newLiked ? '1' : '0';
            icon.textContent = newLiked ? '❤️' : '🤍';
            countEl.textContent = newLiked ? previousCount + 1 : Math.max(previousCount - 1, 0);
            btn.disabled = true;

            try {
                const res = await fetch(`/image/${imageId}/like`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        Accept: 'application/json',
                    },
                });

                const data = await res.json();

                btn.dataset.userLiked = data.liked ? '1' : '0';
                icon.textContent = data.liked ? '❤️' : '🤍';
                countEl.textContent =
                    typeof data.count === 'number' ? data.count : countEl.textContent;
            } catch (error) {
                btn.dataset.userLiked = wasLiked ? '1' : '0';
                icon.textContent = wasLiked ? '❤️' : '🤍';
                countEl.textContent = previousCount;

                const message =
                    error && error.message ? error.message : 'An error occurred. Please try again.';

                const toast = document.createElement('div');
                toast.id = 'toast-error';
                toast.className =
                    'fixed top-4 left-1/2 -translate-x-1/2 w-[90%] md:left-auto md:right-4 md:translate-x-0 md:w-auto bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md z-50 transform transition-transform duration-300 ease-in-out';
                toast.innerHTML = `
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <p class="text-sm md:pr-3">${message}</p>
                    </div>

                    <button type="button" class="absolute top-0 right-1 text-red-700" onclick="closeToast('toast-error')">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                `;
                setupAutoCloseToast('toast-error', 5000);
                main.prepend(toast);
            } finally {
                btn.disabled = false;
            }
        });
    });
});
