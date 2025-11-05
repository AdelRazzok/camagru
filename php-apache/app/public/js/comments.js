document.addEventListener('DOMContentLoaded', () => {
    const main = document.querySelector('main');
    const userLogged = main && main.dataset.userLogged === '1';
    const toggleCommentsBtns = document.querySelectorAll('.toggle-comments-btn');
    const addCommentForms = document.querySelectorAll('.add-comment-form');

    toggleCommentsBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            if (!userLogged) {
                return;
            }

            const imageId = btn.dataset.imageId;
            const commentsSection = document.getElementById(`comments-section-${imageId}`);
            const commentsList = document.getElementById(`comments-${imageId}`);

            commentsSection.classList.toggle('hidden');

            if (
                commentsList.children.length === 0 &&
                !commentsSection.classList.contains('hidden')
            ) {
                loadComments(imageId);
            }
        });
    });

    addCommentForms.forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const imageId = form.dataset.imageId;
            const contentInput = form.querySelector('input[name="content"]');
            const content = contentInput.value.trim();

            if (!content) {
                showToast('Please enter a comment.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('content', content);

            const response = await fetch(`/image/${imageId}/comment`, {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                const error = await response.json();
                showToast(error.error || 'Failed to post comment.', 'error');
                return;
            }

            const data = await response.json();

            if (data.success) {
                const commentsList = document.getElementById(`comments-${imageId}`);

                if (commentsList.innerHTML.includes('No comments yet')) {
                    commentsList.innerHTML = '';
                }

                const commentElement = createCommentElement(data.comment);
                commentsList.insertAdjacentHTML('beforeend', commentElement);

                const article = document.querySelector(`article[data-image-id="${imageId}"]`);
                const newCount = parseInt(article.dataset.commentCount) + 1;
                article.dataset.commentCount = newCount;

                const commentCountSpan = document.querySelector(
                    `.toggle-comments-btn[data-image-id="${imageId}"] .comment-count`
                );
                commentCountSpan.textContent = newCount;

                contentInput.value = '';
                showToast('Comment posted successfully.', 'success');
            }
        });
    });
});

async function loadComments(imageId) {
    try {
        const response = await fetch(`/image/${imageId}/comments`);

        if (!response.ok) {
            showToast('Failed to load comments.', 'error');
            return;
        }

        const data = await response.json();
        const commentsList = document.getElementById(`comments-${imageId}`);

        if (data.comments.length === 0) {
            commentsList.innerHTML =
                '<p class="text-center text-gray-500 text-sm py-4">No comments yet</p>';
            commentsList.classList.remove('hidden');
            return;
        }

        let commentsHTML = '';
        data.comments.forEach((comment) => {
            commentsHTML += createCommentElement(comment);
        });

        commentsList.innerHTML = commentsHTML;
        commentsList.classList.remove('hidden');
    } catch (error) {
        showToast('An error occurred.', 'error');
    }
}

function createCommentElement(comment) {
    return `
        <div class="flex gap-2 border-b p-3 last:border-b-0">
            <div class="flex-1">
                <p class="font-semibold text-sm">${comment.author}</p>
                <p class="text-sm text-gray-700">${comment.content}</p>
                <p class="text-xs text-gray-500 mt-1">${comment.created_at}</p>
            </div>
        </div>
    `;
}

function showToast(message, type) {
    const main = document.querySelector('main');
    const toast = document.createElement('div');

    if (type === 'success') {
        toast.id = 'toast-success';
        toast.className =
            'fixed top-4 left-1/2 -translate-x-1/2 w-[90%] md:left-auto md:right-4 md:translate-x-0 md:w-auto bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md z-50 transform transition-transform duration-300 ease-in-out';
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i>
                <p class="text-sm md:pr-3">${message}</p>
            </div>
    
            <button type="button" class="absolute top-0 right-1 text-green-700" onclick="closeToast('${toast.id}')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        `;
    } else if (type === 'error') {
        toast.id = 'toast-error';
        toast.className =
            'fixed top-4 left-1/2 -translate-x-1/2 w-[90%] md:left-auto md:right-4 md:translate-x-0 md:w-auto bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md z-50 transform transition-transform duration-300 ease-in-out';
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p class="text-sm md:pr-3">${message}</p>
            </div>
    
            <button type="button" class="absolute top-0 right-1 text-red-700" onclick="closeToast('${toast.id}')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        `;
    }

    setupAutoCloseToast(toast.id, 5000);
    main.prepend(toast);
}
