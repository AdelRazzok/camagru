document.querySelectorAll('.delete-post-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        if (confirm('Are you sure you want to delete this post?')) {
            const imageId = this.dataset.imageId;

            const response = await fetch(`/image/${imageId}`, {
                method: 'DELETE',
            });

            if (!response.ok) {
                const error = await response.json();
                showToast(error.error || 'Failed to delete post.', 'error');
                return;
            }

            const postsContainer = document.querySelector('.post-container');
            const remainingPosts = postsContainer?.querySelectorAll('article').length - 1;
            const currentPage = new URLSearchParams(window.location.search).get('page') || '1';

            if (remainingPosts === 0 && currentPage > 1) {
                window.location.href = `?page=${currentPage - 1}`;
            } else {
                location.reload();
            }
        }
    });
});
