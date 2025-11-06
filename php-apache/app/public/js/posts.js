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

            location.reload();
        }
    });
});
